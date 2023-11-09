<?php

namespace App\Http\Middleware;

use App\Constants\OpenAi;
use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAmountOfMessageRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $projectId = $request->project_id;
        $project = Project::query()->find($projectId);
        $user = $project->user()->getQuery()->with('customer', 'customer.subscriptionPlan')->first();
        $maxMessage = $user->customer->subscriptionPlan->max_message;
        $countMessage = $user->message_count;

        if ($maxMessage >= $countMessage) {
            return $next($request);
        }

        return response()->stream(function () {
            foreach(explode(' ', OpenAi::LIMIT_MESSAGE) as $text) {
                echo "$text ";
                flush();
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
