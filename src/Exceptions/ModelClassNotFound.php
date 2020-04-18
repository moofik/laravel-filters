<?php

namespace Moofik\LaravelFilters\Exceptions;


use Exception;
use Throwable;

class ModelClassNotFound extends Exception
{
    public function __construct($class, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Model %s not found', $class),
            $code,
            $previous
        );
    }
}
