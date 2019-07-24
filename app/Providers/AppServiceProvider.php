<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;
use App\Services;
use App\Services\Interfaces;

class AppServiceProvider extends ServiceProvider
{
    protected $applicationServices = [
        Interfaces\SettingServiceInterface::class => Services\SettingService::class,
        Interfaces\TimesheetServiceInterface::class => Services\TimesheetService::class,
        Interfaces\UserServiceInterface::class => Services\UserService::class,
        Interfaces\RoleServiceInterface::class => Services\RoleService::class,
        Interfaces\DatatableServiceInterface::class => Services\DatatableService::class,
    ];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->applicationServices as $interface => $service) {
            $this->app->bind($interface, $service);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
    }
}
