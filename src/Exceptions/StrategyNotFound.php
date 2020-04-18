<?php


namespace Moofik\LaravelFilters\Exceptions;


use Throwable;

class StrategyNotFound extends \Exception
{
    public function __construct(string $class, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('%s not found.', $class),
            $code,
            $previous
        );
    }
}
