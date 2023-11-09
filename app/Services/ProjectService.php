<?php

namespace App\Services;

use App\Constants\TrainingType;
use App\Jobs\Training;
use App\Models\Customer;
use App\Models\LineChannels;
use App\Models\Project;
use App\Models\ProjectContent;
use App\Models\Vector;
use App\Repositories\ProjectContentRepository;
use App\Repositories\ProjectRepository;
use App\Services\OpenAiServices\OpenAiService;
use App\Services\OpenAiServices\PineconeService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectService
{
    /**
     * @var OpenAiService $openAiService
     */
    protected OpenAiService $openAiService;

    /**
     * @var PineconeService $pineconeService
     */
    protected PineconeService $pineconeService;

    /**
     * @var ProjectRepository
     */
    protected ProjectRepository $projectRepository;

        /**
     * @var ProjectContentRepository
     */
    protected ProjectContentRepository $projectContentRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * Project service constructor.
     *
     * @param OpenAiService
     */
    public function __construct(OpenAiService $openAiService,
        PineconeService $pineconeService,
        ProjectRepository $projectRepository,
        UserService $userService,
        ProjectContentRepository $projectContentRepository)
    {
        $this->openAiService = $openAiService;
        $this->pineconeService = $pineconeService;
        $this->projectRepository = $projectRepository;
        $this->userService = $userService;
        $this->projectContentRepository = $projectContentRepository;
    }

    public function listProject()
    {
        return $this->projectRepository->list();
    }

    /**
     * Create new project
     *
     * @param Request $request
     * @return Project $project
     */
    public function create(Request $request): Project
    {
        $user = $this->userService->detail($request->get('user_id'));

        $project = new Project([
            'user_uuid' => $user->uuid,
            'name' => $request->get('name')
        ]);

        DB::transaction(function () use ($user, $project, $request) {
            $project->save();
            /**
             * Save text input content
             */
            if ($request->get('contents')) {
                foreach($request->get('contents') as $content) {
                    if ($content['page_name']) {
                        $project->projectContents()->create([
                            'name' => $content['page_name'],
                            'text' => $content['content'],
                            'type' => TrainingType::TEXT
                        ]);
                    }
                    continue;
                }
            }

            /**
             * Save file input content
             */
            if ($request->get('inputFiles')) {
                foreach($request->get('inputFiles') as $file) {
                    $project->projectContents()->create([
                        'name' => $file['name'],
                        'text' => $file['text'],
                        'type' => TrainingType::FILE
                    ]);
                }
            }   
            
            /**
             * Save link content.
             */
            if ($request->get('link_contents') && $request->get('content_links')) {
                for ($i = 0; $i < count($request->get('link_contents')); $i++ ) {
                    $project->projectContents()->create([
                        'name' => $request->get('link_contents')[$i],
                        'text' => $request->get('content_links')[$i],
                        'type' => TrainingType::LINK
                    ]);
                }
            }
            
            if (!$user->customer) {
                Customer::query()->updateOrInsert(
                    [
                        'user_uuid' => $user->uuid
                    ],
                    [
                        'user_uuid' => $user->uuid,
                        'subscription_plan_id' => 1,
                        'start_date' => date('Y-m-d H:i:s'),
                        'due_date' => date('Y-m-d H:i:s', strtotime('+1 month')),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }

            if ($user->requestStatistics->isEmpty()) {
                $user->requestStatistics()->create([
                    'subscription_plan_id' => 1,
                    'total_request' => 0,
                    'month' => Carbon::now()->month
                ]);
            }
        });

        /**
         * Add background job training after create.
         */
        Training::dispatch($project);

        return $project;
    }

    /**
     * Update project
     *
     * @param Request $request
     * @return Project $project
     */
    public function update(string $id, Request $request): Project
    {
        $project = $this->projectRepository->detail($id);

        DB::transaction(function () use ($project, $request) {

            /**
             * Save project.
             */
            $project->update([
                'name' => $request->get('name'),
                'processing_status' => 0
            ]);
            
            $project->projectContents()->delete();

            $vectors = Vector::query()->where('project_id', $project->id)->get();

            if (!empty($vectors)) {
                foreach($vectors as $vector) {
                    $vector->delete();
                    $this->pineconeService->delete($vector['id']);
                }
            }

            /**
             * Save text input content
             */
            if ($request->get('contents')) {
                foreach($request->get('contents') as $content) {
                    if ($content['page_name']) {
                        $project->projectContents()->create([
                            'name' => $content['page_name'],
                            'text' => $content['content'],
                            'type' => TrainingType::TEXT
                        ]);
                    } 
                    continue;  
                }
            }

            /**
             * Save content files.
             */
            if ($request->get('inputFiles')) {

                foreach($request->get('inputFiles') as $file) {
                    
                        $project->projectContents()->create([
                            'name' => $file['name'],
                            'text' => $file['text'],
                            'type' => TrainingType::FILE
                        ]);
                    
                    continue;
                } 
            }   

            /**
             * Save link content.
             */
            if ($request->get('link_contents') && $request->get('content_links')) {
                for ($i = 0; $i < count($request->get('link_contents')); $i++ ) {
                    $project->projectContents()->create([
                        'name' => $request->get('link_contents')[$i],
                        'text' => $request->get('content_links')[$i],
                        'type' => TrainingType::LINK
                    ]);
                }
            }
        });

        /**
         * Add background job training after update.
         */
        Training::dispatch($project);

        return $project;
    }


    /**
     * Training data project
     *
     * @return bool
     */
    public function trainingAi(): bool
    {
        $unprocessedProjects = $this->projectRepository->listUnprocess();

        if (count($unprocessedProjects) > 0){
            foreach ($unprocessedProjects as $project) {
                $this->handleEmbedingProject($project);
            }
        }

        return true;
    }

    /**
     * Training job
     *
     * @param Project $project
     */
    public function trainingData(Project $project): void
    {
        $this->handleEmbedingProject($project);
    }

    /**
     * List projects of current user
     *
     * @param string $userId
     * @return Collection
     */
    public function listProjectsByUserUId(string $userUId): Collection
    {
        return $this->projectRepository->listAllProjectsByUserUId($userUId);
    }

    /**
     * Chunk size text to array sub text.
     *
     * @param string $input
     * @return array
     */
    private function handleTextInput(string $input): array
    {
        $result = array();
        $remainingText = $input;
        $maxLength = 8000;

        while (strlen($remainingText) > 0) {
            $splitLength = min($maxLength, strlen($remainingText));
            $result[] = substr($remainingText, 0, $splitLength);
            $remainingText = substr($remainingText, $splitLength);
        }

        return $result;
    }

    /**
     * Embeding project file
     * 
     * @param Project $project
     * @return void
     */
    public function handleEmbedingProject(Project $project): void
    {
        $contents = ProjectContent::query()->where('project_id', $project->id)->get()->toArray();


        if (count($contents) > 0) {
            foreach ($contents as $content) {
                
                $inputEmbeddingArray = $this->handleTextInput($content['text']);
                Log::info($inputEmbeddingArray);

                /**
                 * Embedding input content to multiple vector
                 */
                if (count($inputEmbeddingArray) > 0) {
                    foreach($inputEmbeddingArray as $input) {
                    Log::info(gettype($input));
                    /** Embedding project content to vector */
                        $embeddingResult = $this->openAiService->embedding($input);

                        /* save vector detail to database */
                        $data = Vector::query()->create(
                            [
                                'project_id' => $project->id,
                                'text' => $input,
                                'value' => json_encode($embeddingResult['embedding']),
                                'token' => $embeddingResult['token'],
                                'content_id' => $content['id']
                            ]
                        );

                        Log::info($content['id']);

                        /**
                         * Delete old vector.
                         */
                        // $this->pineconeService->delete($data->id);

                        $vectors = [
                            'vectors' => [
                                'id' => (string) $data->id,
                                'values' => json_decode($data->value, true),
                                'metadata' => [
                                    'page_id' => $content['id'],
                                    'page_name' => $content['name'],
                                    'project_id' => $data->project_id,
                                    'project_name' => $project->name,
                                    'text' => $input,
                                    'token' => $embeddingResult['token']
                                ]
                            ]
                        ];

                        /**
                         * Save vector to pincone.
                         */
                        $this->pineconeService->save($vectors);

                        $project->update([
                            'processing_status' => 1
                        ]);
                    }
                }    
            }
        }
    }

    public function detail(string $id): Project
    {
        return $this->projectRepository->detail($id);
    }

    /**
     * Delete project
     * 
     * @param string $id
     * @return Project
     */
    public function deleteProject(string $id): Project
    {
        $project = $this->projectRepository->detail($id);

        if ($project) {
            $project->delete();
            $project->projectContents()->delete();
        }

        $vectors = Vector::query()->where('project_id', $project->id)->get();

        if (!empty($vectors)) {
            foreach($vectors as $vector) {
                $vector->delete();
                $this->pineconeService->delete($vector['id']);
            }
        }

        // $lineChanel = LineChannels::query()->where('project_id', $id)->first();
        // $lineChanel->delete();

        return $project;
    }
}
