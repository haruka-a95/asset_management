<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $menu = [
                ['title' => 'ホーム', 'route' => route('welcome')],
            ];

            $view->with('menu', $menu);
        });
    }

    public function register()
    {
        //
    }
}
