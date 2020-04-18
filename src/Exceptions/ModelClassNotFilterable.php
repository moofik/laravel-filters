<?php

namespace Moofik\LaravelFilters\Exceptions;


use Moofik\LaravelFilters\Filter\Strategy\Strategy;
use Moofik\LaravelFilters\Traits\Filterable;
use Throwable;

class ModelClassNotFilterable extends \Exception
{
    public function __construct($class, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('%s should use %s trait', $class, Filterable::class),
            $code,
            $previous
        );
    }
}
