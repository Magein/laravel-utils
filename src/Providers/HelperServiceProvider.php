<?php

namespace Magein\LaravelUtils\Providers;

use Illuminate\Support\ServiceProvider;
use Magein\LaravelUtils\Commands\MakeModel;
use Magein\LaravelUtils\Commands\MakeModelProperty;
use Magein\LaravelUtils\Commands\MakeModelValidator;

/**
 * 参考地址 https://learnku.com/laravel/t/35930
 */
class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (is_file(__DIR__ . '/../Common.php')) {
            require_once __DIR__ . '/../Common.php';
        }

        foreach (glob(app_path('Helpers') . '/*.php') as $file) {
            require_once $file;
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 加载命令
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModel::class,
                MakeModelProperty::class,
                MakeModelValidator::class,
            ]);
        }
    }
}
