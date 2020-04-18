<?php


namespace Moofik\LaravelFilters\Filter\Strategy;


use Moofik\LaravelFilters\Query\Query;

interface Strategy
{
    /**
     * @param Query $query
     * @return Query
     */
    public function handle(Query $query): Query;
}
