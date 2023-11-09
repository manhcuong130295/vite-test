<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ZaloChannelService;
use App\Transformers\SuccessResource;
use App\Http\Requests\Api\Zalo\StoreRequest;
use App\Adapters\Zalos\ZaloWebhookService;
use App\Events\HasZaloProjectEvent;


class ZaloController extends Controller
{

    /**
     * @var ZaloWebhookService
     */
    private $zaloWebhookService;

    /**
     * @var ZaloChannelService
     */
    private $zaloChannelService;

    public function __construct(
        ZaloWebhookService $zaloWebhookService,
        ZaloChannelService $zaloChannelService
    )
    {
        $this->zaloWebhookService = $zaloWebhookService;
        $this->zaloChannelService = $zaloChannelService;
    }

    /**
     * Handle webhook line callback
     *
     * @param Request $request
     */
    public function handleWebhook(Request $request)
    {
        return $this->zaloWebhookService->webhook($request);

    }


    /**
     * Create new project
     *
     * @param StoreRequest $request
     * @return SuccessResource
     */
    public function create(StoreRequest $request): SuccessResource
    {
        $result = $this->zaloChannelService->updateOrCreate($request);
        if ($result) {
            try {
                event(new HasZaloProjectEvent($result));
            } catch (\Throwable $th) {
                Log::info('===SLACK NOTIFICATION ERROR===' . $th->getMessage());
            }
        }
        return SuccessResource::make($result);
    }
}
