<?php

namespace Imanee;

use Imanee\Model\FilterInterface;

class FilterResolver
{
    /**
     * @var FilterInterface[]
     */
    protected $filters;

    /**
     * @param FilterInterface[] $filters
     */
    public function __construct(array $filters = [])
    {
        $this->filters = [];

        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * @param $filter_name
     *
     * @return bool|FilterInterface
     */
    public function resolve($filter_name)
    {
        foreach ($this->filters as $filter) {
            if ($filter->getName() === $filter_name) {
                return $filter;
            }
        }

        return false;
    }

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }
}
