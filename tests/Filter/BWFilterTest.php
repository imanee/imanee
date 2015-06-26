<?php

/**
 * GD BWFilter Test
 */

namespace imanee\tests\Filter\GD;

use Imanee\Imanee;
use Imanee\ImageResource\GDResource;
use Imanee\ImageResource\ImagickResource;
use Imanee\Filter\GD;
use Imanee\Filter\Imagick;

class BWFilterTest extends \imanee\tests\Filter\AbstractFilterTest
{

    public function testFilterForGD()
    {
        // Perform the B&W comparison
        $imanee = $this->getImanee(new GDResource);
        $filter = new GD\BWFilter($imanee);
        $filter->apply($imanee);

        $this->assertImageSimilarTo(
            __DIR__ . '/../resources/img01_bw_gd.jpg',
            $imanee,
            'Image conversion result didn\'t match reference image.'
        );
    }

    public function testFilterForImagick()
    {
        // Perform the B&W comparison
        $imanee = $this->getImanee(new ImagickResource);
            $filter = new Imagick\BWFilter($imanee);
        $filter->apply($imanee);

        $this->assertImageSimilarTo(
            __DIR__ . '/../resources/img01_bw_imagick.jpg',
            $imanee,
            'Image conversion result didn\'t match reference image.'
        );
    }
}
