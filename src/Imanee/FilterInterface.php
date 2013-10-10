<?php
/**
 * Filter Interface.
 */

namespace Imanee;

use Imanee\Imanee;

interface FilterInterface {

    /**
     * Apply the filter to the Imagick resource
     * @param \Imagick $resource
     * @return \Imagick The modified resource
     */
    public function apply(\Imagick $resource, array $options = []);

    /**
     * Gets the filter name. It must be a unique identifier
     * @return string The identifier of the filter
     */
    public function getName();
}