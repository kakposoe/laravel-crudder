<?php namespace Kakposoe\Crudder\Models;

use Illuminate\Support\Facades\Schema;

class Model
{
    /**
     * Create a new crud file
     */
    public static function create($class)
    {
        if (!$class) {
            throw new Exception('There is an issue');
        }

        /**
         * Get the class
         */
        $model = (new $class);

        $fillable = $model->getFillable();

        return $fillable;

        /**
         * Get a full list of all fields
         */
        //$fields = Schema::getColumnListing($tablename);
    }
}
