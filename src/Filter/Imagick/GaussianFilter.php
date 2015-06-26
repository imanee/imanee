<?php

namespace Imanee\Filter\Imagick;

use Imanee\Imanee;
use Imanee\Model\FilterInterface;

class GaussianFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Imanee $imanee, array $options = [])
    {
        /** @var \Imagick $resource */
        $resource = $imanee->getResource()->getResource();

        $options = array_merge(['radius' => 2, 'sigma' => 2], $options);

        return $resource->gaussianBlurImage($options['radius'], $options['sigma']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_gaussian';
    }
}
