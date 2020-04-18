<?php


namespace Moofik\LaravelFilters\Exceptions;


use Exception;
use Moofik\LaravelFilters\Filter\Filter;
use Throwable;

class ShouldExtendsFilter extends Exception
{
    public function __construct(string $class, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('%s should extends %s', $class, Filter::class),
            $code,
            $previous
        );
    }
}
