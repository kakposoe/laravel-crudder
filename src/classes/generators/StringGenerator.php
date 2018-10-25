<?php

namespace Kakposoe\Crudder\Classes\Generators;

class StringGenerator implements Generator
{
    public function create(string $fieldName, array $options)
    {
        extract($options);

        $field = '<input type="text" name="' . $fieldName . '" value="" ';

        if (!empty($options['placeholder'])) {
            $field .= 'placeholder="' . $options['placeholder'] . '"';
        }

        $field .= '>';

        return $field;
    }
}
