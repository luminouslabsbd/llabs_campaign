<?php
namespace Luminouslabs\Installer\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LuminousLabsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $defer = false;

    public function register(): void
    {
        $this->publishFiles();
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        Route::prefix('api')
            ->middleware(['api'])
            ->group(__DIR__ . '/../Routes/api.php');

        $this->loadViewsFrom(__DIR__ . '/../Views', 'luminouslabs');
        $this->publishMigrations();
    }

    protected function publishFiles()
    {
        $this->publishes([
            __DIR__ . '/../assets' => public_path('luminouslabs'),
        ]);
        $this->publishes([
            __DIR__ . '/../Views' => base_path('resources/views/luminouslabs/installer'),
        ]);
    }

    private function publishMigrations()
    {
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'migrations');
    }

}
