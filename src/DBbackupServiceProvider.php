<?php 

namespace Mustaq\DBbackup;

use Illuminate\Support\ServiceProvider;
use Mustaq\DBbackup\Console\BackupDatabase;

class DBbackupServiceProvider extends ServiceProvider
{

	public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->commands([
            BackupDatabase::class,
        ]);
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