<?php


namespace Moofik\LaravelFilters\Filter\Runner;


use Illuminate\Database\Eloquent\Builder;
use Moofik\LaravelFilters\Filter\Filter;

class FilterRunner
{
    private $filters = [];

    /**
     * @param Filter $filter
     */
    public function add(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @param $builder
     * @return Builder
     */
    public function run($builder): Builder
    {
        return $builder;
    }
}
