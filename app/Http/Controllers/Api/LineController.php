<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\LineChannelService;
use App\Transformers\SuccessResource;
use App\Http\Requests\Api\Line\StoreRequest;
use App\Adapters\Lines\LineWebhookService;
use App\Events\HasLineProjectEvent;

class LineController extends Controller
{

    /**
     * @var LineWebhookService
     */
    private $lineWebhookService;

    /**
     * @var LineChannelService
     */
    private $lineChannelService;

    public function __construct(
        LineWebhookService $lineWebhookService,
        LineChannelService $lineChannelService
    )
    {
        $this->lineWebhookService = $lineWebhookService;
        $this->lineChannelService = $lineChannelService;
    }

    /**
     * Handle webhook line callback
     *
     * @param Request $request
     */
    public function handleWebhook(Request $request)
    {
        return $this->lineWebhookService->webhook($request);

    }


    /**
     * Create new project
     *
     * @param StoreRequest $request
     * @return SuccessResource
     */
    public function create(StoreRequest $request): SuccessResource
    {
        $result = $this->lineChannelService->updateOrCreate($request);
        if ($result) {
            try {
                event(new HasLineProjectEvent($result));
            } catch (\Throwable $th) {
                Log::info('===SLACK NOTIFICATION ERROR===' . $th->getMessage());
            }
        }
        return SuccessResource::make($result);
    }
}
