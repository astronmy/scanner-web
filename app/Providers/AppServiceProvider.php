<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.navigation', function ($view) {
            $user = auth()->user();
            if (!$user) {
                $view->with('navEvents', collect());
                return;
            }

            $navEvents = $user->isAdmin()
                ? Event::orderBy('start_date')->get()
                : $user->events()->orderBy('start_date')->get();

            $view->with('navEvents', $navEvents);
        });
    }
}
