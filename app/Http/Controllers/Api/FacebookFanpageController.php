<?php

namespace App\Http\Controllers\Api;

use App\Events\HasFacebookFanpageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Messenger\StoreRequest;
use App\Services\FacebookFanpageService;
use App\Transformers\SuccessResource;
use Illuminate\Support\Facades\Log;

class FacebookFanpageController extends Controller
{
    /**
     * @var FacebookFanpageService
     */
    protected $facebookFanpageService;

    /**
     * FacebookFanpageController construct
     * 
     * @param FacebookFanpageService
     */
    public function __construct(FacebookFanpageService $facebookFanpageService)
    {
        $this->facebookFanpageService = $facebookFanpageService;
    }

    public function create(StoreRequest $request)
    {
        $result = $this->facebookFanpageService->updateOrCreate($request);
        if ($result) {
            try {
                event(new HasFacebookFanpageEvent($result));
            } catch (\Throwable $th) {
                Log::info('===SLACK NOTIFICATION ERROR===' . $th->getMessage());
            }
        }
        return SuccessResource::make($result);
    }
}
