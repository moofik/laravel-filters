<?php


namespace Moofik\LaravelFilters\Exceptions;


use Exception;

class StrategyNotSuitable extends Exception
{
    public function __construct(string $strategy, string $filter, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Strategy %s not suitable for %s filter', $strategy, $filter),
            $code,
            $previous
        );
    }
}
