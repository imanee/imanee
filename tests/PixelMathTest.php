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

    public function testShouldReturnBestFitForLandscapeDimensions()
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
    }

    public function testShouldReturnBestFitForPortraitDimensions()
    {
        $this->assertEquals(
            [
                'width' => 80,
                'height' => 100
            ],
            $this->pixelMath->getBestFit(
                80,
                100,
                800,
                1000
            )
        );
    }

    public function testShouldReturnMaxFitForLandscapeDimensions()
    {
        $this->assertEquals(
            [
                'width' => 1000,
                'height' => 800
            ],
            $this->pixelMath->getMaxFit(
                1000,
                800,
                100,
                80
            )
        );
    }

    public function testShouldReturnMaxFitForPortraitDimensions()
    {
        $this->assertEquals(
            [
                'width' => 800,
                'height' => 1000
            ],
            $this->pixelMath->getMaxFit(
                800,
                1000,
                80,
                100
            )
        );
    }
}
