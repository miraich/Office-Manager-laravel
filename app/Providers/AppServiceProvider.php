<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Project;
use App\Policies\GroupPolicy;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Carbon;
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
        setlocale(LC_ALL, 'ru_RU.UTF-8');
        Carbon::setLocale('ru_RU.UTF-8');
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Group::class, GroupPolicy::class);
    }
}
