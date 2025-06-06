<?php

namespace App\Providers;

use App\Contracts\UserRepositoryInterface;
use App\Repositories\DoctrineUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, DoctrineUserRepository::class);
    }
}
