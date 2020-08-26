<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CrudGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate
    {name : Class (singular) for example User}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CRUD files.';



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
     * @return String
     */
    public function handle() //TODO clear this up
    {
        $name = $this->argument('name');
        $this->controller($name);
        $this->model($name);
        $this->request($name);
        $this->migration($name);

        \File::append(base_path('routes/api.php'), 'Route::resource(\'' . STR::plural(strtolower($name)) . "', '{$name}Controller');");
        $this->info('Created ' . $name . ' model, controller, route, request and migration.');
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function model($name)
    {
        $modelTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/{$name}.php"), $modelTemplate);
    }

    protected function controller($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $name,
                strtolower(STR::plural($name)),
                strtolower($name)
            ],
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $controllerTemplate);
    }

    protected function request($name)
        {
        $requestTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Request')
    );

    if(!file_exists($path = app_path('/Http/Requests')))
        mkdir($path, 0777, true);

    file_put_contents(app_path("/Http/Requests/{$name}Request.php"), $requestTemplate);
    }

    protected function migration($name) {
        $migrationTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{tableNameUpperCase}}',
            ],
            [
                $name,
                strtolower(STR::plural($name)),
                strtolower($name),
                STR::plural($name)
            ],
            $this->getStub('Migration')
        );
        $date = date("Y_m_d_") . rand(000000, 100000);
        $migrationName = strtolower(STR::plural($name));

        file_put_contents(database_path("/migrations/{$date}_create_{$migrationName}_table.php"), $migrationTemplate);

    }

}
