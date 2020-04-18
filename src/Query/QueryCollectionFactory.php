<?php

namespace Moofik\LaravelFilters\Query;


use Illuminate\Http\Request;

class QueryCollectionFactory
{
    /**
     * @param Request $request
     * @return QueryCollection
     */
    public function create(Request $request): QueryCollection
    {
        $queryCollection = new QueryCollection();

        preg_match(
            '#.*?\?(.*)#ius',
            $request->server('REQUEST_URI'),
            $query
        );

        if (!isset($query[1])) {
            return new QueryCollection();
        }

        $queryParts = explode('&', $query[1]);

        if ($queryParts[0] === '') {
            return new QueryCollection();
        }

        foreach ($queryParts as $part) {
            $compositePart = explode('=', $part);
            list($key, $value) = $compositePart;
            $filterValues[$key] = $value;
        }

        foreach ($filterValues as $filterKey => $filterValue) {
            preg_match(
                '#(.*?)\[(.*?)\]#ius',
                $filterKey,
                $matches
            );

            $operator = isset($matches[1]) ? $matches[1] : '=';

            if (isset($matches[1]) && isset($matches[2])) {
                $query = new Query($matches[1], $matches[2], $filterValue);
            } else {
                $query = new Query($filterKey, $operator, $filterValue);
            }

            $queryCollection->add($query);
        }

        return $queryCollection;
    }
}
