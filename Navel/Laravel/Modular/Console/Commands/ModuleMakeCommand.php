<?php

namespace Navel\Laravel\Modular\Console\Commands;

use Illuminate\Console\Command;

class ModuleMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new module';

    protected $files = [
        'Kernel.php',
        'config/config.php',
        'Controllers/IndexController.php',
        'Providers/ServiceProvider.php',
        'Providers/RouteServiceProvider.php',
    ];

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
        $module = ucfirst($this->argument('name'));
        $moduleName = strtolower($module);

        $file = dirname(__DIR__).'/stubs/Module.zip';
        $newfile = base_path(config('modular.path')).'/'.$module;

        if(file_exists($newfile)) {
            echo "Module $module creation fails : module already exists.\n\r";
            return;
        }

        $zip = new \ZipArchive;
        if ($zip->open($file) === TRUE) {
            $zip->extractTo($newfile);
            $zip->close();
        } else {
            echo "Module $module creation fails : error extracting stub.\n\r";
            return;
        }

        foreach ($this->files as $file) {
            $content = file_get_contents($newfile.'/'.$file);
            $content = str_replace('{module}', $module, $content);
            $content = str_replace('{moduleName}', $moduleName, $content);
            file_put_contents($newfile.'/'.$file, $content);
        }

        rename($newfile.'/config/module.php', $newfile.'/config/'.$moduleName.'.php');

        echo "Module ".$module." created successfully.\n\r";
    }
}
