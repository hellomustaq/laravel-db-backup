<?php 

namespace Mustaq\DBbackup;

use Illuminate\Support\ServiceProvider;

class DBbackupServiceProvider extends ServiceProvider
{

	public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
       
    }
}