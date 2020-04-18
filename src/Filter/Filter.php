<?php

namespace Moofik\LaravelFilters\Filter;

use Illuminate\Database\Eloquent\Builder;
use Moofik\LaravelFilters\Filter\Strategy\Strategy;
use Moofik\LaravelFilters\Query\Query;
use Moofik\LaravelFilters\Query\QueryCollection;

abstract class Filter
{
    /**
     * @var QueryCollection
     */
    protected $queryCollection;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var string
     */
    private $field;

    /**
     * @var Strategy
     */
    private $strategy;

    /**
     * Filter constructor.
     * @param QueryCollection $queryCollection
     * @param string $field
     * @param Strategy $strategy
     */
    public function __construct(QueryCollection $queryCollection, string $field, ?Strategy $strategy)
    {
        $this->queryCollection = $queryCollection;
        $this->field = $field;
        $this->strategy = $strategy;
    }

    /**
     * @return bool
     */
    public function isSuitable()
    {
        $this->query = $this->queryCollection->findQueryByField($this->field);

        return $this->query !== null;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    abstract public function apply(Builder $builder): Builder;
}
