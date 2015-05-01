<?php

namespace Imanee\Model;

interface FilterInterface
{
    /**
     * Apply the filter to the Imagick resource
     * @param \Imagick $resource
     * @param array $options
     * @return \Imagick The modified resource
     */
    public function apply(\Imagick $resource, array $options = []);

    /**
     * Gets the filter name. It must be a unique identifier
     * @return string The identifier of the filter
     */
    public function getName();
}
