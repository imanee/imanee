<?php

namespace Imanee\Filter;

use Imanee\FilterInterface;

class SepiaFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(\Imagick $resource, array $options = [])
    {
        $options = array_merge(['threshold' => 80], $options);

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
