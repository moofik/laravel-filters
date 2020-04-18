<?php


namespace Moofik\LaravelFilters\Query;


class QueryCollection implements \Countable, \Iterator
{
    /**
     * @var array
     */
    private $queryMap;

    /**
     * @var int
     */
    private $position;

    public function __construct()
    {
        $this->queryMap = [];
        $this->position = 0;
    }

    public function count()
    {
        return count($this->queryMap);
    }

    /**
     * @param Query $query
     * @return $this
     */
    public function add(Query $query): self
    {
        $this->queryMap[] = $query;

        return $this;
    }

    /**
     * @param string $field
     * @return Query|null
     */
    public function findQueryByField(string $field): ?Query
    {
        /** @var Query $query */
        foreach ($this->queryMap as $query) {
            if ($query->getField() === $field) {
                return $query;
            }
        }

        return null;
    }

    public function allQueries(): array
    {
        return $this->queryMap;
    }

    public function current()
    {
        return $this->queryMap[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->queryMap[$this->position];
    }

    public function valid()
    {
        return isset($this->queryMap[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
