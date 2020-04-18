<?php


namespace Moofik\LaravelFilters\Exceptions;


use Exception;
use Throwable;

class StrategyNotFound extends Exception
{
    public function __construct(string $class, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('%s not found.', $class),
            $code,
            $previous
        );
    }
}
