<?php
/**
 * Color Filter
 */

namespace Imanee\Filter;


use Imanee\FilterInterface;
use Imanee\Imanee;

class ColorFilter implements FilterInterface{

    /**
     * {@inheritdoc}
     */
    public function apply(\Imagick $resource, array $options = [])
    {
        $options = array_merge( [
            'color'   => 'blue'
        ], $options);

        return $resource->colorizeimage($options['color'], 1);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_color';
    }

}