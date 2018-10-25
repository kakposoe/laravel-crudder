<?php

namespace Kakposoe\Crudder\Classes\Generators;

interface Generator
{
    public function create(string $fieldName, array $options);
}
