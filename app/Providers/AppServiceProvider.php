<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\ThemeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ThemeService::class, function ($app) {
            return new ThemeService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('theme', function ($expression) {
            return "<?php echo app(\\App\\Services\\ThemeManager::getClass($expression)); ?>";
        });
        Blade::directive('themeClass', function ($expression) {
            return "<?php echo \\App\\Services\\ThemeManager::getClass($expression); ?>";
        });
    }
}
