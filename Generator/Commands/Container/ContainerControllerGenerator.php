<?php

namespace Apiato\Core\Generator\Commands\Container;

use Apiato\Core\Generator\GeneratorCommand;
use Apiato\Core\Generator\Interfaces\ComponentsGenerator;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ContainerControllerGenerator
 *
 * @author  Johannes Schobel  <johannes.schobel@googlemail.com>
 */
class ContainerControllerGenerator extends GeneratorCommand implements ComponentsGenerator
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'apiato:container-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a controller for a container';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $fileType = 'Controller';

    /**
     * The structure of the file path.
     *
     * @var  string
     */
    protected $pathStructure = '{container-name}/UI/{user-interface}/Controllers/*';

    /**
     * The structure of the file name.
     *
     * @var  string
     */
    protected $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     *
     * @var  string
     */
    protected $stubName = 'container\controller.stub';

    /**
     * The options which can be passed to the command. All options are optional. You do not need to pass the
     * "--container" and "--file" options, as they are globally handled. Just use the options which are specific to
     * this generator.
     *
     * @var  array
     */
    public $inputs = [
        ['ui', null, InputOption::VALUE_OPTIONAL, 'The user-interface to generate the Controller for.'],
    ];

    /**
     * @return  array
     */
    public function getUserInputs()
    {
        $ui = Str::lower($this->checkParameterOrChoice('ui', 'Select the UI for the controller', ['API', 'WEB']));

        $basecontroller = Str::ucfirst($ui) . 'Controller';

        $model = $this->containerName;
        $entity = Str::lower($model);
        $entities = Pluralizer::plural($entity);

        return [
            'path-parameters' => [
                'container-name'    => $this->containerName,
                'user-interface'    => Str::upper($ui),
            ],
            'stub-parameters' => [
                'container-name'    => $this->containerName,
                'class-name'         => $this->fileName,
                'user-interface'    => Str::upper($ui),
                'base-controller'   => $basecontroller,

                'model'             => $model,
                'entity'            => $entity,
                'entities'          => $entities,
            ],
            'file-parameters' => [
                'file-name'         => $this->fileName,
            ],
        ];
    }

    public function getDefaultFileName()
    {
        return 'Controller';
    }

}