<?php

/**
 * Abstract test for all 
 */

namespace imanee\tests\Filter;

use Imanee\Imanee;
use Imanee\ImageResource\GDResource;
use Imanee\Filter\GD\BWFilter;

abstract class AbstractFilterTest extends \PHPUnit_Framework_TestCase
{

    protected $tmpFile;

    public function setUp()
    {
        $this->tmpName = tempnam(sys_get_temp_dir(), 'Imanee-test-image');
        $this->tmpFile = $this->tmpName.'.jpg';
    }

    public function tearDown()
    {
        if($this->tmpFile && file_exists($this->tmpFile)) {
            unlink($this->tmpFile);
        }
        if($this->tmpName && file_exists($this->tmpName)) {
            unlink($this->tmpName);
        }
    }

    public function getImanee($resource)
    {
        return new Imanee(__DIR__ . '/../resources/img01.jpg', $resource);
    }

    public function assertImageSimilarTo($comparisonFilename, Imanee $imanee, $message)
    {
        $imanee->write($this->tmpFile);
        $imagick1 = new \Imagick($this->tmpFile);
        $imagick2 = new \Imagick($comparisonFilename);

        $compare = $imagick1->compareImages($imagick2, \Imagick::METRIC_MEANSQUAREERROR);
        $this->assertLessThan(0.0001, $compare[1], $message);
    }
}
