<?php

namespace App\Providers;

use App\Models\PortfolioMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            $view->with('unreadMessages', PortfolioMessage::where('is_read', false)->count());
        });
    }
}
