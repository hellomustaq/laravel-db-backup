<?php

namespace Mustaq\DBbackup\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database Backup';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $process;

    public function __construct()
    {
        parent::__construct();

        $path = storage_path('app/backups');
   
        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0755, true, true);
        }

        $this->process = new Process(sprintf(
            'mysqldump -h%s -u%s -p%s %s > %s',
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('app/backups/db-backup-' . date('Y_m_d_H_i_s') . '.sql')
        ));
    }

    public function handle()
    {
        try {
            $file = new Filesystem;
            $file->cleanDirectory('storage/app/backups');

            $this->process->mustRun();

            // $fileName = $this->getLastModifiedFileName(storage_path('app/backups/'));
            // $data=[];

            // Mail::send('mail', $data, function($message) use ($fileName)
            // {
            //     $message->to('hellomstq@gmail.com');
            //     $message->subject('Database daily backup');
            //     $message->attach(public_path('backup/'.$fileName));
            // });

            $this->info('The database backup has been proceed successfully!');
        } catch (ProcessFailedException $exception) {
            $this->error($exception->getMessage());
            $this->error('The database backup process has been failed!');
        }
    }

    public function getLastModifiedFileName($path){
        $latest_ctime = 0;
        $latest_filename = '';    

        $d = dir($path);
        while (false !== ($entry = $d->read())) {
          $filepath = "{$path}/{$entry}";
          // could do also other checks than just checking whether the entry is a file
          if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
            $latest_ctime = filectime($filepath);
            $latest_filename = $entry;
          }
        }
        return $latest_filename;
    }

}