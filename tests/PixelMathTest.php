<?php

namespace Imanee\Tests;

use Imanee\PixelMath;
use PHPUnit_Framework_TestCase;

class PixelMathTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PixelMath
     */
    private $pixelMath;

    public function setUp()
    {
        $this->pixelMath = new PixelMath();
    }

    /**
     * This test test both best fit and max fit since the are expected to
     * behave the same when dimensions match
     */
    public function testShouldReturnDimensionsThatExactlyFitInTargetImage()
    {
        $this->assertEquals(
            [
                'width' => 100,
                'height' => 80
            ],
            $this->pixelMath->getBestFit(
                100,
                80,
                1000,
                800
            )
        );

        $this->assertEquals(
            [
                'width' => 100,
                'height' => 80
            ],
            $this->pixelMath->getMaxFit(
                100,
                80,
                1000,
                800
            )
        );
    }

    public function testShouldReturnDimensionsUsingTargetDimensionsAsAMaximumWhenProportionsDoNotMatch()
    {
        $this->assertEquals(
            [
                'width' => 20,
                'height' => 80
            ],
            $this->pixelMath->getBestFit(
                100,
                80,
                200,
                800
            )
        );
    }

    public function testShouldReturnDimensionsUsingTargetDimensionsAsAMinimumWhenProportionsDoNotMatch()
    {
        $this->assertEquals(
            [
                'width' => 800,
                'height' => 100
            ],
            $this->pixelMath->getMaxFit(
                80,
                100,
                1600,
                200
            )
        );
    }
}
