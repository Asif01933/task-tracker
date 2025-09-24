<?php 
namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Interfaces\TeamRepositoryInterface;
use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Infrastructure\Repositories\EloquentTeamRepository;
use App\Infrastructure\Repositories\EloquentMemberRepository;
use App\Infrastructure\Repositories\EloquentTaskRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MemberRepositoryInterface::class, EloquentMemberRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, EloquentTeamRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
    }
}
