<?php

namespace App\Http\Middleware;

use App\Constants\OpenAi;
use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExistProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $projectId = $request->project_id;
        $existProject = Project::query()->where('id', $projectId)->exists();

        if ($existProject) {
            return $next($request);
        }

        return response()->stream(function () {
            foreach(explode(' ', OpenAi::PROJECT_NOT_FOUND) as $text) {
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
