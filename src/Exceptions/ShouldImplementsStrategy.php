<?php


namespace Moofik\LaravelFilters\Exceptions;


use Exception;
use Moofik\LaravelFilters\Filter\Strategy\Strategy;
use Throwable;

class ShouldImplementsStrategy extends Exception
{
    public function __construct($class, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('%s should implements %s', $class, Strategy::class),
            $code,
            $previous
        );
    }
}
