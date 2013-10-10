<?php
/**
 * Black and White Filter
 * Convenient way to modulate the image for removing saturation
 */

namespace Imanee\Filter;


use Imanee\FilterInterface;
use Imanee\Imanee;

class BWFilter implements FilterInterface{

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