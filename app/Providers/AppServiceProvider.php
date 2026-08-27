<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        Paginator::useTailwind();
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.tailwind');

        // Share active counts with sidebar and layout for real-time notification badges
        \Illuminate\Support\Facades\View::composer(['components.sidebar', 'layouts.app'], function ($view) {
            $user = auth()->user();
            $sidebarPendingCount = 0;
            $sidebarReadyCount = 0;

            if ($user) {
                if ($user->department_id) {
                    $sidebarPendingCount = \App\Models\MutationForm::where('to_department_id', $user->department_id)
                        ->where('status', 'waiting_receiver')
                        ->count();
                }

                if ($user->hasRole('inventory', 'admin', 'super_admin')) {
                    $sidebarReadyCount = \App\Models\MutationForm::where('status', 'ready_for_execution')->count();
                }
            }

            $view->with([
                'sidebarPendingCount' => $sidebarPendingCount,
                'sidebarReadyCount' => $sidebarReadyCount,
            ]);
        });
    }
}

