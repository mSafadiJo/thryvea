<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name : The name of the service (e.g., Location/LocationService)}';

    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path('Services/' . $name . '.php');

        if (File::exists($path)) {
            $this->error('Service already exists!');
            return;
        }

        $directory = dirname($path);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $namespace = 'App\\Services\\' . str_replace('/', '\\', dirname($name));
        $class = class_basename($name);

        $template = <<<EOT
<?php

namespace $namespace;

class $class
{
    //
}
EOT;

        File::put($path, $template);

        $this->info("Service created: {$path}");
    }
}
