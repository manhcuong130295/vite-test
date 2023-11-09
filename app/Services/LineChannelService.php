<?php

namespace App\Services;

use App\Constants\ProviderStatus;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\LineChannelRepository;
use Illuminate\Support\Facades\DB;
use App\Models\LineChannels;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class LineChannelService
{
    /**
     * @var LineChannelRepository
     */
    protected $lineChannelRepository;

    /**
     * CustomerService constructor.
     *
     * @param LineChannelRepository $customerRepository
     */
    public function __construct(
        LineChannelRepository $lineChannelRepository
    ) {
        $this->lineChannelRepository = $lineChannelRepository;
    }

    /**
     * Save customer
     * @param Request $request
     *
     */
    public function updateOrCreate(Request $request)
    {
        //upload file icon
        if ($request->hasFile('provider_icon')) {
            $folderPath = 'public/provider/icon';
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
                Storage::setVisibility($folderPath, 'public');
            }
            $file = $request->file('provider_icon');
            $path = $file->store($folderPath);
            $pathIcon = Storage::url($path);
        }

        $project_id = $request->get('project_id');

        $data = [
            'project_id' => $request->get('project_id'),
            'provider_name' => $request->get('provider_name'),
            'provider_description' => $request->get('provider_description'),
            'status' => ProviderStatus::PENDING
        ];
        if (empty($request->get('provider_id'))) {
            $data['uuid'] = Str::uuid();
        }
        if (!empty($pathIcon)) {
            $data['path_icon'] = $pathIcon;
        }

        return $this->lineChannelRepository->updateOrCreate($project_id, $data);
    }

    /**
     * Get by project id
     * @param string projectId
     *
     */
    public function getByProjectId(string $projectId)
    {
        return $this->lineChannelRepository->getByProjectId($projectId);
    }

    /**
     * Get by uuid
     * @param string uuid
     *
     */
    public function getByUuid(string $uuid)
    {
        return $this->lineChannelRepository->getById($uuid);
    }

    /**
     * Delete line channel
     * @param string $uuid
     */
    public function deleteCustomer($uuid)
    {
        return $this->lineChannelRepository->deleteCustomer($uuid);
    }
}
