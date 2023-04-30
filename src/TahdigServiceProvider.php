<?php

namespace Bslm\Tahdig;

use Bslm\Tahdig\Components\ReserveFood;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class TahdigServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'tahdig');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'tahdig');
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'tahdig');
        $this->publishes([__DIR__ . '/config/config.php' => config_path('tahdig.php')]);
        Livewire::component('tahdig::components.reserve-food', ReserveFood::class);
    }

    public function register()
    {

    }
}