<?php

namespace Moofik\LaravelFilters\Traits;

trait Filterable
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }
}
