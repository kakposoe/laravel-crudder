<?php

namespace Kakposoe\Crudder\Classes;

class PasswordGenerator
{
    public function create(string $fieldName, array $options)
    {
        extract($options);

        $field = '<input type="password" name="' . $fieldName . '" value="" ';

        if (!empty($options['placeholder'])) {
            $field .= 'placeholder="' . $options['placeholder'] . '"';
        }

        $field .= '>';

        return $field;
    }
}
