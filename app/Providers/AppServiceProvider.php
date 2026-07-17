<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        Gate::define('is-admin', fn ($user) => $user->role === 'admin');
        Gate::define('is-teacher', fn ($user) => in_array($user->role, ['teacher', 'admin']));
        Gate::define('is-student', fn ($user) => $user->role === 'student');
    }
}
