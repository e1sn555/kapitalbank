<?php

namespace Kapitalbank;

use Illuminate\Support\ServiceProvider;

class KapitalbankServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            $this->configPath() => config_path('kapitalbank.php')
        ], 'kapitalbank');
    }

    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'kapitalbank');
        $this->app->bind(KapitalbankContract::class, Kapitalbank::class);
    }

    /**
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__ .'/../config/kapitalbank.php';
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [
            Kapitalbank::class
        ];
    }
}
