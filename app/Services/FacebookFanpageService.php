<?php

namespace App\Services;

use App\Constants\ProviderStatus;
use App\Models\FacebookFanpage;
use App\Repositories\FacebookFanpageRepository;
use Illuminate\Http\Request;

class FacebookFanpageService
{
    /**
     * @var FacebookFanpageRepository
     */
    protected $facebookFanpageRepository;

    /**
     * FacebookFanpage Service Construct
     * 
     * @param FacebookFanpageRepository
     */
    public function __construct(FacebookFanpageRepository $facebookFanpageRepository)
    {
        $this->facebookFanpageRepository = $facebookFanpageRepository;
    }

    /**
     * Save facebook fanpage
     * 
     * @param $request
     */
    public function updateOrCreate(Request $request): ?FacebookFanpage
    {
        $data = [
            'project_id' => $request->get('project_id'),
            'name' => $request->get('name'),
            'page_id' => $request->get('page_id'),
            'access_token' => $request->get('access_token'),
            'status' => ProviderStatus::PENDING
        ];

        return $this->facebookFanpageRepository->updateOrCreate($request->get('project_id'), $data);
    }

    /**
     * Find fanpage by id
     */
    public function findById(string $id): ?FacebookFanpage
    {
        return $this->facebookFanpageRepository->findById($id);
    }

    /**
     * Check exist
     */
    public function checkExists(string $id): bool
    {
        return $this->facebookFanpageRepository->checkExists($id);
    }

    public function getByProjectId(string $id): ?FacebookFanpage
    {
        return $this->facebookFanpageRepository->getByProjectId($id);
    }
}
