<?php 
namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Infrastructure\Repositories\EloquentMemberRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MemberRepositoryInterface::class, EloquentMemberRepository::class);
    }
}
