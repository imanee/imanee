<?php

namespace Imanee\Filter\GD;

use Imanee\Imanee;
use Imanee\Model\FilterInterface;

class GaussianFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Imanee $imanee, array $options = [])
    {
        /** @var resource $resource */
        $resource = $imanee->getResource()->getResource();

        imagefilter($resource, IMG_FILTER_GAUSSIAN_BLUR);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_gaussian';
    }
}
