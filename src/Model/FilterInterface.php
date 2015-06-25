<?php

namespace Imanee\Model;

use Imagick;
use Imanee\Imanee;

/**
 * Effect which can be applied to an image.
 */
interface FilterInterface
{
    /**
     * Apply the filter to the Imagick resource.
     *
     * @param Imanee $imanee
     * @param array  $options
     *
     * @return Imagick
     */
    public function apply(Imanee $imanee, array $options = []);

    /**
     * Gets the filter name. It must be a unique identifier.
     *
     * @return string
     */
    public function getName();
}
