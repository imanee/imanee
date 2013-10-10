<?php
/**
 * Modulate Filter - changes image brightness, hue and saturation
 * If parameters are not specified, changes the saturation for 50%
 */

namespace Imanee\Filter;


use Imanee\FilterInterface;
use Imanee\Imanee;

class ModulateFilter implements FilterInterface{

    /**
     * {@inheritdoc}
     */
    public function apply(\Imagick $resource, array $options = [])
    {
        $options = array_merge( [
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