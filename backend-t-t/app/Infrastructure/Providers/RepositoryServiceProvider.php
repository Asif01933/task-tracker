<?php 
namespace App\Infrastructure\Providers;

use App\Domain\Interfaces\InvitationReporistoryInterface;
use App\Domain\Interfaces\LabelRepositoryInterface;
use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Domain\Interfaces\ReportReceiverInterface;
use App\Domain\Interfaces\ReportRepositoryInterface;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Domain\Interfaces\TeamRepositoryInterface;
use App\Infrastructure\Repositories\EloquentInvitationRepository;
use App\Infrastructure\Repositories\EloquentLabelRepository;
use App\Infrastructure\Repositories\EloquentMemberRepository;
use App\Infrastructure\Repositories\EloquentReportReceiverRepository;
use App\Infrastructure\Repositories\EloquentReportRepository;
use App\Infrastructure\Repositories\EloquentTaskRepository;
use App\Infrastructure\Repositories\EloquentTeamRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MemberRepositoryInterface::class, EloquentMemberRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, EloquentTeamRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
        $this->app->bind(ReportReceiverInterface::class, EloquentReportReceiverRepository::class);
        $this->app->bind(InvitationReporistoryInterface::class, EloquentInvitationRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, EloquentReportRepository::class);
        $this->app->bind(LabelRepositoryInterface::class, EloquentLabelRepository::class);
    }
}
