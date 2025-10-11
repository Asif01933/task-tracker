<?php 
namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Interfaces\ReportReceiverInterface;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Domain\Interfaces\TeamRepositoryInterface;
use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Domain\Interfaces\ReportRepositoryInterface;
use App\Domain\Interfaces\InvitationReporistoryInterface;
use App\Infrastructure\Repositories\EloquentTaskRepository;
use App\Infrastructure\Repositories\EloquentTeamRepository;
use App\Infrastructure\Repositories\EloquentMemberRepository;
use App\Infrastructure\Repositories\EloquentInvitationRepository;
use App\Infrastructure\Repositories\EloquentReportReceiverRepository;
use App\Infrastructure\Repositories\EloquentReportRepository;

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
    }
}
