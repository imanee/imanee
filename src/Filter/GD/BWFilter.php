<?php

namespace Imanee\Filter\GD;

use Imanee\Imanee;
use Imanee\Model\FilterInterface;

class BWFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Imanee $imanee, array $options = [])
    {
        /** @var resource $resource */
        $resource = $imanee->getResource()->getResource();

        return imagefilter($resource, IMG_FILTER_GRAYSCALE);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_bw';
    }
}
