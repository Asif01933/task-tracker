<?php

namespace App\Providers;

use App\Models\Label;
use App\Models\Report;
use App\Models\ReportReceiver;
use App\Models\Task;
use App\Models\Team;
use App\Policies\LabelPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ReportReceiverPolicy;
use App\Policies\TaskPolicy;
use App\Policies\TeamPolicy;
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
        Gate::policy(Team::class, TeamPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Label::class, LabelPolicy::class);
        Gate::policy(Report::class, ReportPolicy::class);
        Gate::policy(ReportReceiver::class, ReportReceiverPolicy::class);
    }
}
