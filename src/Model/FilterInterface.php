<?php

namespace Imanee\Model;

use Imanee\Imanee;

interface FilterInterface
{
    /**
     * Apply the filter to the Imagick resource
     * @param Imanee $imanee Imanee object
     * @param array $options
     * @return \Imagick The modified resource
     */
    public function apply(Imanee $imanee, array $options = []);

    /**
     * Gets the filter name. It must be a unique identifier
     * @return string The identifier of the filter
     */
    public function getName();
}
