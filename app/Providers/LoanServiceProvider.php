<?php

namespace App\Providers;

use App\Repositories\RepositoryInterface;
use App\Repositories\LoanRepository;
use Illuminate\Support\ServiceProvider;

class LoanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app-bind(
        //     'App\Repositories\RepositoryInterface',
        //     'App\Repositories\LoanRepository'
        //     );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
