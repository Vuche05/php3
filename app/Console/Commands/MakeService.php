<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeService extends Command
{
    protected $signature = 'make:service {name} {--force : Overwrite if the file already exists}';
    protected $description = 'Generate a new service class in app/Services';

    public function handle(): void
    {
        $name = Str::studly($this->argument('name'));
        $path = app_path("Services/{$name}.php");

        if (file_exists($path) && !$this->option('force')) {
            $this->components->error("❌ Service {$name} already exists!");
            return;
        }        

        if (!is_dir(app_path('Services'))) {
            mkdir(app_path('Services'), 0755, true);
        }

        file_put_contents($path, $this->generateStub($name));

        $this->components->info("✅ Service {$name} created successfully at app/Services/");
    }

    protected function generateStub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Services;

class {$name}
{
    // Write your service logic here
}
PHP;
    }
}
