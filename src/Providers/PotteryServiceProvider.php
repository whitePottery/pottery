<?php

namespace Pottery\Providers;

use App\Console\Commands\CreatePackage;
use Illuminate\Support\ServiceProvider;

class PotteryServiceProvider extends ServiceProvider
{

    private $copyList = [

        'migrations'  => 'migrations',
        'Controllers' => 'Http/Controllers/Pottery',
        'views'       => 'views/pottery',
        'js'          => 'js/pottery',
    ];



    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $pathPackege = base_path('vendor/whitepottery/pottery/src/');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Pottery\Console\Commands\ExampleCommand::class,
                \Pottery\Console\Commands\MakePackage::class,
                \Pottery\Console\Commands\CreatePackage::class,
                \Pottery\Console\Commands\HelpCommand::class,
            ]);
        }

        $this->loadViewsFrom($pathPackege . 'resources/views', 'pottery');

        //$this->loadRoutesFrom(__DIR__.'/../routes/pottery.php');

        foreach ($this->copyList as $key => $pathTo) {

            $pathFrom = $pathPackege.'copy/' . $key;

            if (file_exists($pathFrom)) {
                $this->publishes([
                    $pathFrom => database_path($pathTo),
                ], 'public');
            }

        }

        // $migrations_path = __DIR__ . '/../copy/migrations';
        // if (file_exists($migrations_path)) {
        //     $this->publishes([
        //         $migrations_path => database_path('migrations'),
        //     ], 'public');
        // }

        // $migrations_path = __DIR__ . '/../copy/Controllers';
        // if (file_exists($migrations_path)) {
        //     $this->publishes([
        //         $migrations_path => app_path('Http/Controllers/Pottery'),
        //     ], 'public');
        // }

        // $migrations_path = __DIR__ . '/../copy/views';
        // if (file_exists($migrations_path)) {
        //     $this->publishes([
        //         $migrations_path => resource_path('views/pottery'),
        //     ], 'public');
        // }

        // $js_path = __DIR__ . '/../copy/js';
        // if (file_exists($js_path)) {
        //     $this->publishes([
        //         $js_path => public_path('js/pottery'),
        //     ], 'public');
        // }

        /*
    $this->publishes([
    __DIR__ . '/../copy/Controllers/Pottery' => app_path('Http/Controllers'),
    ], 'public');
     */

    }

}
