<?php

namespace Kakposoe\Crudder\Classes;

use DB;

class Crudder
{
    /** @var */
    public $registeredModels;

    /** @var */
    public $model;

    /** @var */
    public $overrides;

    
    /**
     * Initiate class
     */
    public function __construct()
    {
        $this->registeredModels = collect(config('crudder.models'));
    }

    /**
     * Process request
     *
     * @param string $routeName
     *
     * @return string
     */
    public function processRequest(string $routeName)
    {
        if (!$this->isRegisteredModel($routeName)) {
            return '404';
        }

        return $this->getContent(
            $this->getModel($routeName)
        );
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
        $this->registeredModels
            ->each(function ($model, $key) use ($routeName) {
                if (isset($model['route_path'])
                    && $model['route_path'] == $routeName) {
                    $this->model        = $model;
                    $this->model['key'] = $key;
                    return false;
                }
            });

        return !empty($this->model);
    }

    /**
     * Get the model of the route
     *
     * @param string  $modelName
     *
     * @param stdClass
     */
    public function getModel()
    {
        $model_name = isset($this->model['class']) ? $this->model['class'] : 'App\\' . $this->model['key'];

        // TODO: if class does not exist, throw exception
        if (!class_exists($model_name)) {
            return '404';
        }

        return new $model_name;
    }

    /**
     * Get the content of the model
     *
     * @param string  $modelName
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
     * @param   string  $field
     * @param   string  $tableName
     * 
     * @return  string
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
