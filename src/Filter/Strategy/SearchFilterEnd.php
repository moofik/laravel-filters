<?php

namespace Moofik\LaravelFilters\Filter\Strategy;

use Moofik\LaravelFilters\Query\Query;

class SearchFilterEnd implements Strategy
{
    /**
     * @param Query $query
     * @return Query
     */
    public function handle(Query $query): Query
    {
        return new Query(
            $query->getField(),
            'LIKE',
            '%' . $query->getValue()
        );
    }
}
