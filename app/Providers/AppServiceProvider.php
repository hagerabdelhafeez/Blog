<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Session;

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
        //Redirect an Authentacated user to dashboard
        RedirectIfAuthenticated::redirectUsing(function () {
            return route('admin.dashboard');
        });

        //Redirect an unAuthentacated user to admin login page
        Authenticate::redirectUsing(function () {
            Session::flash('fail', 'You must be logged in to access admin area. Please login first.');
            return route('admin.login');
        });
    }
}
