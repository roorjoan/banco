<?php

namespace App\Providers;

use App\Http\Resources\TransactionResource;
use App\Repositories\AccountRepository;
use App\Repositories\AccountRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TransactionResource::withoutWrapping();
    }
}
