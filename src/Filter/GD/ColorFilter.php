<?php

namespace Imanee\Filter\GD;

use Imanee\ImageResource\GDPixel;
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
        /** @var resource $resource */
        $resource = $imanee->getResource()->getResource();
        $options = array_merge(['color' => 'blue'], $options);

        $pixel = new GDPixel($options['color']);
        imagefilter($resource, IMG_FILTER_COLORIZE, $pixel->channelR, $pixel->channelG, $pixel->channelB);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_color';
    }
}
