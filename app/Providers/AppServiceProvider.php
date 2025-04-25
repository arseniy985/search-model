<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OpenRouterService;
use App\Services\AIServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Log\LogManager;
use Monolog\Handler\NullHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AIServiceInterface::class, function ($app) {
            return new OpenRouterService(config('services.openrouter.key'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Это полностью отключит логирование
        Log::extend('null', function ($app) {
            $monolog = new \Monolog\Logger('null');
            $monolog->pushHandler(new NullHandler());
            return new LogManager($app);
        });
        
        config(['logging.default' => 'null']);
    }
}
