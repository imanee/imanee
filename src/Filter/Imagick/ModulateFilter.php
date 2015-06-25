<?php

namespace Imanee\Filter\Imagick;

use Imagick;
use Imanee\Imanee;
use Imanee\Model\FilterInterface;

/**
 * Adjusts the brightness, hue and saturation of an image.
 *
 * Without options this will adjust the saturation to 50%.
 */
class ModulateFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Imanee $imanee, array $options = [])
    {
        /** @var Imagick $resource */
        $resource = $imanee->getResource()->getResource();

        $options = array_merge([
            'brightness' => 100,
            'saturation' => 50,
            'hue'        => 100,
        ], $options);

        return $resource->modulateimage(
            $options['brightness'],
            $options['saturation'],
            $options['hue']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_modulate';
    }
}
