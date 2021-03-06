<?php

namespace App\Providers;
use App\Models\Device;
use App\Models\Organization;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->bootEloquentMorphs();
        Schema::defaultStringLength(191);
    }
    private function bootEloquentMorphs()
    {
        Relation::morphMap([
            Device::TABLE => Device::class,
            Organization::TABLE => Organization::class,
        ]);
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
