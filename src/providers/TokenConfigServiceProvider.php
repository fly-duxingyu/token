<?php

namespace Duxingyu\Token\providers;

use Illuminate\Support\ServiceProvider;

class TokenConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //获取配置文件
        $config_path = config_path() . '/tokenConfig.php';
        if (file_exists($config_path)) {
            // 合并配置文件
            $this->mergeConfigFrom(
                $config_path,
                'tokenConfig'
            );
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Config path.
        $config_path = config_path() . '/tokenConfig.php';
        if (file_exists($config_path)) {
            // Publish config.
            $this->publishes(
                [$config_path => config_path('tokenConfig.php')],
                'tokenConfig'
            );
        }

    }
}
