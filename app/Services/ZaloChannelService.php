<?php

namespace App\Services;

use App\Constants\ProviderStatus;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\ZaloChannelRepository;
use Illuminate\Support\Facades\DB;
use App\Models\ZaloChannels;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ZaloChannelService
{
    /**
     * @var ZaloChannelRepository
     */
    protected $zaloChannelRepository;

    /**
     * CustomerService constructor.
     *
     * @param ZaloChannelRepository $zaloChannelRepository
     */
    public function __construct(
        ZaloChannelRepository $zaloChannelRepository
    ) {
        $this->zaloChannelRepository = $zaloChannelRepository;
    }

    /**
     * Save customer
     * @param Request $request
     *
     */
    public function updateOrCreate(Request $request)
    {
        $project_id = $request->get('project_id');

        $data = [
            'project_id' => $request->get('project_id'),
            'name' => $request->get('name'),
            'oa_id' => $request->get('oa_id'),
            'client_id' => $request->get('client_id'),
            'client_secret' => $request->get('client_secret'),
            'access_token' => $request->get('access_token'),
            'refresh_token' => $request->get('refresh_token'),
            'status' => ProviderStatus::PENDING
        ];
        if (empty($request->get('zalo_id'))) {
            $data['uuid'] = Str::uuid();
        }

        return $this->zaloChannelRepository->updateOrCreate($project_id, $data);
    }

    /**
     * Get by project id
     * @param string projectId
     *
     */
    public function getByProjectId(string $projectId)
    {
        return $this->zaloChannelRepository->getByProjectId($projectId);
    }

    /**
     * Get by uuid
     * @param string uuid
     *
     */
    public function getByUuid(string $uuid)
    {
        return $this->zaloChannelRepository->getById($uuid);
    }

    /**
     * update token by uuid
     * @param string $uuid
     * @param array $data
     */
    public function updateTokenByUuid(string $uuid, array $data)
    {
        return $this->zaloChannelRepository->updateTokenByUuid($uuid, $data);
    }
    /**
     * Delete line channel
     * @param string $uuid
     */
    public function deleteCustomer($uuid)
    {
        return $this->zaloChannelRepository->deleteCustomer($uuid);
    }
}
