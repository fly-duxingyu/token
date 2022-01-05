<?php

namespace Duxingyu\Token\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * 注册
 */
class TokenConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     */
    public function register()
    {
        //获取配置文件
        $config_path = config_path() . '/duxingyuConfig.php';
        if (file_exists($config_path)) {
            // 合并配置文件
            $this->mergeConfigFrom(
                $config_path,
                'duxingyuConfig'
            );
        }
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        // Config path.
        $config_path = config_path() . '/duxingyuConfig.php';
        if (file_exists($config_path)) {
            // Publish config.
            $this->publishes(
                [$config_path => config_path('duxingyuConfig.php')],
                'duxingyuConfig'
            );
        }

    }
}
