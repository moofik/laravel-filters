<?php

namespace Moofik\LaravelFilters\Filter;


use Illuminate\Database\Eloquent\Builder;

class SearchFilter extends Filter
{
    public function apply(Builder $builder): Builder
    {
        return $builder;
    }
}
