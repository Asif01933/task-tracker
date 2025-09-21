<?php 
namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Interfaces\TeamRepositoryInterface;
use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Infrastructure\Repositories\EloquentTeamRepository;
use App\Infrastructure\Repositories\EloquentMemberRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MemberRepositoryInterface::class, EloquentMemberRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, EloquentTeamRepository::class);
    }
}
