<?php
/**
 * Sepia Filter
 */

namespace Imanee\Filter;


use Imanee\FilterInterface;
use Imanee\Imanee;

class SepiaFilter implements FilterInterface{

    /**
     * {@inheritdoc}
     */
    public function apply(\Imagick $resource, array $options = [])
    {
        $options = array_merge( [
            'threshold' => 80
        ], $options);

        return $resource->sepiatoneimage($options['threshold']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_sepia';
    }

}