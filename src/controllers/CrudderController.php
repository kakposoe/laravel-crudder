<?php

namespace Kakposoe\Crudder\Controllers;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use\Kakposoe\Crudder\Classes\StringGenerator;
use Kakposoe\Crudder\Models\Model;

class CrudderController extends Controller
{
    /** @var */
    public $registeredModels;

    /** @var */
    public $model;

    /** @var */
    public $overrides;

    public function __construct()
    {
        $this->registeredModels = collect(config('crudder.models'));
    }

    public function index(Request $request)
    {
        if (!$this->isRegisteredModel($request->route_name)) {
            return '404';
        }

        $content = $this->getContent(
            $this->getModel($request->route_name)
        );

        return view('crudder::template', compact('content'));
    }

    /**
     * Check if model is a registered model
     *
     * @param string $model Model
     *
     * @return bool
     */
    public function isRegisteredModel(string $routeName)
    {
        $result = false;

        $this->registeredModels
            ->each(function ($model, $key) use ($routeName, &$result) {
                if (isset($model['route_name'])
                    && $model['route_name'] == $routeName) {
                    $result = true;
                    return false;
                }
            });

        if ($result) {
            return true;
        }

        return $this->registeredModels
            ->keys()
            ->map(function ($model_key, $key) {
                return strtolower($model_key);
            })
            ->contains($routeName);
    }

    public function getModel($modelName)
    {
        $model_name = 'App\\' . ucfirst($modelName);

        // TODO: if class does not exist, throw exception
        if (!class_exists($model_name)) {
            return '404';
        }

        return new $model_name;
    }

    /**
     * Get the content of the model
     *
     * @param string $modelName
     *
     * @return string
     */
    public function getContent($modelName)
    {
        $this->model = new $modelName;

        $content = '';

        if ($wrapperClass = config('crudder.wrapper') ?: 'form') {
            $content .= '<form class="' . $wrapperClass . '">';
        }

        /**
         * Get table name
         */
        $tableName = with(new $modelName)->getTable();

        /**
         * Add field content
         */
        collect($this->model->getFillable())
            ->each(function ($field, $key) use ($tableName, &$content) {
                $content .= $this->getField($field, $tableName);
            });

        $content .= '</form>';

        return $content;
    }

    /**
     * Get field html
     *
     * @param string $field
     * @param string $tableName
     * 
     * @return string
     */
    public function getField($field, $tableName)
    {
        $options = [];

        if (isset($this->model->crudders)
            && !empty($this->model->crudders[$field])) {
            $options = $this->model->crudders[$field];
        }

        $type = $this->getType($options, $tableName, $field);

        $className = '\Kakposoe\Crudder\Classes\\Generators\\' . ucfirst($type) . 'Generator';
        return (new $className)->create($field, $options);
    }

    /**
     * Return field type
     *
     * @param   array   $options
     * @param   string  $tableName
     * @param   string  $field
     *
     * @return string
     */
    public function getType($options, $tableName, $field)
    {
        return !empty($options) && isset($options['type']) ? $options['type']
            : DB::getSchemaBuilder()->getColumnType($tableName, $field);
    }
}
