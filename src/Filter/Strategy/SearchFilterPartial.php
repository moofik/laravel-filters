<?php

namespace Moofik\LaravelFilters\Filter\Strategy;

use Moofik\LaravelFilters\Query\Query;

class SearchFilterPartial implements Strategy
{
    /**
     * @param Query $query
     * @return Query
     */
    public function handle(Query $query): Query
    {
        return $query;
    }
}
