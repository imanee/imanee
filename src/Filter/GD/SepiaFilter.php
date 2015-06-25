<?php

namespace Imanee\Filter\GD;

use Imanee\Imanee;
use Imanee\Model\FilterInterface;

/**
 * Makes an image brownish.
 */
class SepiaFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Imanee $imanee, array $options = [])
    {
        /** @var resource $resource */
        $resource = $imanee->getResource()->getResource();

        imagefilter($resource, IMG_FILTER_GRAYSCALE);
        imagefilter($resource, IMG_FILTER_COLORIZE, 100, 50, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_sepia';
    }
}
