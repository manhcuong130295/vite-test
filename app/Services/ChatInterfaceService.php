<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\ChatInterfaceRepository;
use Illuminate\Support\Facades\DB;
use App\Models\ChatInterface;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ChatInterfaceService
{
    /**
     * @var ChatInterfaceRepository
     */
    protected $chatInterfaceRepository;

    /**
     * ChatInterfaceService constructor.
     *
     * @param ChatInterfaceRepository $chatInterfaceRepository
     */
    public function __construct(
        ChatInterfaceRepository $chatInterfaceRepository
    ) {
        $this->chatInterfaceRepository = $chatInterfaceRepository;
    }

    /**
     * Save customer
     * @param Request $request
     *
     */
    public function updateOrCreate(Request $request)
    {
        // upload file icon
        if ($request->hasFile('chatbot_picture')) {
            $folderPath = 'public/chatbot/';
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
                Storage::setVisibility($folderPath, 'public');
            }
            $file = $request->file('chatbot_picture');
            $path = $file->store($folderPath);
            $pathIcon = Storage::url($path);
        }

        $project_id = $request->get('project_id');
        $chatInterfaceId = $request->get('chat_interface_id');

        $data = [
            'project_id' => $request->get('project_id'),
            'chatbot_name' => $request->get('chatbot_name'),
            'theme_color' => $request->get('theme_color'),
            'chatbot_picture_active' => $request->get('chatbot_picture_active'),
            'initial_message' => $request->get('initial_message'),
            'suggest_message' => $request->get('suggest_message'),

        ];
        if (!empty($pathIcon)) {
            $data['chatbot_picture'] = $pathIcon;
        }
        if ($chatInterfaceId) {
            return $this->chatInterfaceRepository->update($chatInterfaceId, $data);
        } else {
            return $this->chatInterfaceRepository->store($data);
        }
    }

    /**
     * Get by project id
     * @param string projectId
     *
     */
    public function getByProjectId(string $projectId)
    {
        return $this->chatInterfaceRepository->getByProjectId($projectId);
    }

    /**
     * Get by id
     * @param int id
     *
     */
    public function getById(int $id)
    {
        return $this->chatInterfaceRepository->getById($id);
    }
}
