<?php

namespace Imanee\Filter\Imagick;

use Imanee\Imanee;
use Imanee\Model\FilterInterface;

/**
 * Adds color to greyscale images.
 */
class ColorFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Imanee $imanee, array $options = [])
    {
        /** @var \Imagick $resource */
        $resource = $imanee->getResource()->getResource();
        $options = array_merge(['color' => 'blue'], $options);

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
