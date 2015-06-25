<?php

namespace Imanee\Filter\Imagick;

use Imanee\Imanee;
use Imanee\Model\FilterInterface;

/**
 * Removes color from images.
 */
class BWFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Imanee $imanee, array $options = [])
    {
        /** @var \Imagick $resource */
        $resource = $imanee->getResource()->getResource();

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
