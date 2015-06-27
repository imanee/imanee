<?php

namespace Imanee\Model;

use Imanee\Model\FilterInterface;

/**
 * Applies filters to an image.
 */
interface ImageFilterableInterface
{
    /**
     * Loads resource filters.
     *
     * @return FilterInterface[] Returns an array with the available filters for this resource.
     */
    public function loadFilters();
}
