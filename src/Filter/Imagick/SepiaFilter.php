<?php

namespace Imanee\Filter\Imagick;

use Imanee\Imanee;
use Imanee\Model\FilterInterface;

class SepiaFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Imanee $imanee, array $options = [])
    {
        $bw = new BWFilter;
        $bw->apply($imanee);

        /** @var \Imagick $resource */
        $resource = $imanee->getResource()->getResource();

        $options = array_merge(['threshold' => 80], $options);

        return $resource->sepiaToneImage($options['threshold']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_sepia';
    }
}
