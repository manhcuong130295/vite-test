<?php

namespace App\Adapters\Zalos;

use App\Adapters\Zalos\ZaloAbstract;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Transformers\SuccessResource;
use App\Jobs\ReplyQuestionZaloJob;

class ZaloWebhookService extends ZaloAbstract
{
    /**
     * handle webhook line channel callback
     *
     * @param Request $request
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('Before start queue reply question zalo');
            $channelId = $request->input('id');
            $data = $request->all();
            if (!empty($channelId)) {
                ReplyQuestionZaloJob::dispatch($channelId, $data)->onQueue('default');
            }
            Log::info('After start queue reply question zalo');

            return new SuccessResource();
        } catch(\UnexpectedValueException $e) {
            Log::error($e);
            return new SuccessResource();
        }
    }
}
