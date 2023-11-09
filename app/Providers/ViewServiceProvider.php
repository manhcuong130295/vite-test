<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register global params for all views.
     */
    public function boot(): void
    {
        
            View::composer('*', function ($view) {
                if (Auth::check()) {
                    $userId = Auth::user()->id;
                    $user = User::query()->where('id', $userId)
                    ->with(['customer', 'customer.subscriptionPlan', 'requestStatistics'])
                    ->withCount('projects')
                    ->first();

                    $customer = $user->customer ? $user->customer : null;
                    $subscriptionPlan = ($user->customer && $user->customer->subscriptionPlan) ? $user->customer->subscriptionPlan : null;
                    $projectsCount = $user->projects_count;

                    $view->with([
                        'subscriptionPlan' => $subscriptionPlan,
                        'customer' => $customer,
                        'projectsCount' => $projectsCount
                    ]);
                }
            });
        
    }
}
