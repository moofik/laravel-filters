<?php

namespace Moofik\LaravelFilters\Filter;


use Illuminate\Database\Eloquent\Builder;
use Moofik\LaravelFilters\Filter\Strategy\SearchFilterEnd;
use Moofik\LaravelFilters\Filter\Strategy\SearchFilterExact;
use Moofik\LaravelFilters\Filter\Strategy\SearchFilterPartial;
use Moofik\LaravelFilters\Filter\Strategy\SearchFilterStart;

class SearchFilter extends Filter
{
    /**
     * @return bool
     */
    protected function isStrategySuitable(): bool
    {
        return ($this->strategy instanceof SearchFilterPartial)
            || ($this->strategy instanceof SearchFilterExact)
            || ($this->strategy instanceof SearchFilterStart)
            || ($this->strategy instanceof SearchFilterEnd);
    }
}
