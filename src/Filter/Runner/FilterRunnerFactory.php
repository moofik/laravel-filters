<?php

namespace Moofik\LaravelFilters\Filter\Runner;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Moofik\LaravelFilters\Exceptions\ModelClassNotFilterable;
use Moofik\LaravelFilters\Exceptions\ModelClassNotFound;
use Moofik\LaravelFilters\Exceptions\ShouldBeImplemented;
use Moofik\LaravelFilters\Exceptions\ShouldExtendsFilter;
use Moofik\LaravelFilters\Exceptions\ShouldImplementsStrategy;
use Moofik\LaravelFilters\Exceptions\StrategyNotFound;
use Moofik\LaravelFilters\Filter\Filter;
use Moofik\LaravelFilters\Filter\Strategy\FilterDefaultStrategy;
use Moofik\LaravelFilters\Filter\Strategy\Strategy;
use Moofik\LaravelFilters\Repository\ModelDiscoverer;
use Moofik\LaravelFilters\Repository\ModelRepository;
use Moofik\LaravelFilters\Query\QueryCollectionFactory;
use Moofik\LaravelFilters\Traits\Filterable;
use Moofik\LaravelFilters\Utils\TraitFinder;

class FilterRunnerFactory
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var TraitFinder
     */
    private $traitFinder;

    /**
     * @var QueryCollectionFactory
     */
    private $queryCollectionFactory;

    /**
     * @var ModelRepository
     */
    private $repository;

    /**
     * QueryFilterFactory constructor.
     * @param Request $request
     * @param ModelRepository $modelRepository
     */
    public function __construct(Request $request, ModelRepository $modelRepository)
    {
        $this->request = $request;
        $this->traitFinder = new TraitFinder();
        $this->queryCollectionFactory = new QueryCollectionFactory();
        $this->repository = $modelRepository;
    }

    /**
     * @param $model
     * @return FilterRunner
     * @throws ModelClassNotFilterable
     * @throws ModelClassNotFound
     * @throws ShouldBeImplemented
     * @throws ShouldExtendsFilter
     * @throws ShouldImplementsStrategy
     * @throws StrategyNotFound
     */
    public function createFor($model)
    {
        $class = null;

        if (is_string($model) && class_exists($model)) {
            $class = $model;
        } elseif ($model instanceof Model) {
            $class = get_class($model);
        } elseif ($model instanceof Builder) {
            $class = get_class($model->getModel());
        } elseif (is_string($model)) {
            $class = $this->repository->find($model);
        }

        if (null === $class || !class_exists($class)) {
            throw new ModelClassNotFound();
        }

        $isFilterable = $this->traitFinder->isTraitUsed($class, Filterable::class);

        if (!$isFilterable) {
            throw new ModelClassNotFilterable($class);
        }

        /** @var Model|Filterable $filterableModel */
        $filterableModel = ($model instanceof Model) ? $model : new $class;
        $filters = $filterableModel->getFilters();
        $queryCollection = $this->queryCollectionFactory->create($this->request);

        $filterRunner = new FilterRunner();
        foreach ($filters as $filter) {
            $filterClassName = $filter[0];
            $filterField = $filter[1];

            $filterStrategyClass = isset($filter[2]) ? $filter[2] : FilterDefaultStrategy::class;

            if (!class_exists($filterStrategyClass)) {
                throw new StrategyNotFound($filterStrategyClass);
            }

            $filterStrategy = new $filterStrategyClass;
            if (!$filterStrategy instanceof Strategy) {
                throw new ShouldImplementsStrategy($filterStrategy);
            }

            /** @var Filter $filter */
            $filter = new $filterClassName($queryCollection, $filterField, $filterStrategy);
            if (!$filter instanceof Filter) {
                throw new ShouldExtendsFilter();
            }

            if ($filter->isSuitable()) {
                $filterRunner->add($filter);
            }
        }

        return $filterRunner;
    }
}
