<?php

namespace Navel\Laravel\Modular\Console\Commands;

use Illuminate\Console\Command;

class ModularDemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modular:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Demo module.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = dirname(__DIR__).'/stubs/Demo.zip';
        $newfile = base_path(config('modular.path')).'/Demo';

        if(file_exists($newfile)) {
            echo "Module Demo creation fails : module already exists.\n\r";
            return;
        }

        $zip = new \ZipArchive;
        if ($zip->open($file) === TRUE) {
            $zip->extractTo($newfile);
            $zip->close();
        } else {
            echo "Module Demo creation fails.\n\r";
            return;
        }

        echo "Module Demo created successfully.\n\r";
    }
}
