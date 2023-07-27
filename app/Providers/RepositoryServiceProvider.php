<?php

namespace App\Providers;

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\IArticleRepository;
use App\Repositories\Author\AuthorRepository;
use App\Repositories\Author\IAuthorRepository;
use App\Repositories\Source\ISourceRepository;
use App\Repositories\Source\SourceRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(IArticleRepository::class, ArticleRepository::class);
        $this->app->singleton(IAuthorRepository::class, AuthorRepository::class);
        $this->app->singleton(ISourceRepository::class, SourceRepository::class);
        $this->app->singleton(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
