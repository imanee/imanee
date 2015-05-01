<?php

namespace Imanee\Filter;

use Imanee\Model\FilterInterface;

/**
 * Black and White Filter
 * Convenient way to modulate the image for removing saturation
 */
class BWFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(\Imagick $resource, array $options = [])
    {
        return $resource->modulateimage(100, 0, 100);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_bw';
    }
}
