<?php

namespace App\Console\Commands;

use Nwidart\Modules\Commands\Make\ControllerMakeCommand  as OriginalMakeControllerCommand; // Adjust path if needed
use Illuminate\Support\Str;

class ExtendedMakeControllerCommand extends OriginalMakeControllerCommand
{
    protected $name = 'module:make-controller'; // Keep the same name to override
    protected $description = 'Create a new module controller';

    protected function getReplacements(): array
    {
        $controllerName = Str::studly(class_basename($this->argument('name'))); // Get studly case controller name
        $moduleName = $this->argument('module');

        return array_merge(parent::getReplacements(), [
            '$CONTROLLER_NAME$' => $controllerName,
        ]);
    }


    protected function stubVariables(): array
    {
        $moduleName = $this->argument('module');
        $controllerName = Str::studly(class_basename($this->argument('name')));

        return [
            'MODULE_NAME' => $moduleName,
            'CONTROLLER_NAME' => $controllerName,
        ];
    }

}
