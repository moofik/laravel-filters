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
    public function apply(Builder $builder)
    {
        $query = $this->strategy->handle($this->query);
        $value = $query->getValue();
        $column = $query->getField();
        $operator = $query->getOperator();

        if ($this->isNestedField($column)) {
            $relationsTree = $this->getRelationsTree($column);

            $relation = $relationsTree[0];
            $field = $relationsTree[1];

            $builder->orWhereHas($relation, function (Builder $query) use ($field, $column, $operator, $value) {
                $query->where($field, $operator, $value);
            });
        } else {
            $builder->orWhere($column, $operator, $value);
        }

        return $builder;
    }

    /**
     * @param string $field
     * @return bool
     */
    protected function isNestedField(string $field)
    {
        $result = explode('.', $field);

        return count($result) > 1;
    }

    /**
     * @param string $field
     * @return array
     */
    protected function getRelationsTree(string $field)
    {
        return explode('.', $field);
    }
}
