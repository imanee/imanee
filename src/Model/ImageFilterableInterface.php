<?php
/**
 * Image Filterable
 * Classes which implement this interface should be able to handle image filters e.g: black&white, sepia etc
 */

namespace Imanee\Model;

use Imanee\Model\FilterInterface;

interface ImageFilterableInterface
{
    /**
     * Loads resource filters
     * @return FilterInterface[] Returns an array with the available filters for this resource
     */
    public function loadFilters();
}
