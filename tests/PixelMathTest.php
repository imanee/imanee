<?php

namespace Imanee\Tests;

use Imanee\Imanee;
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

    /**
     * @dataProvider coordinatesProvider
     */
    public function testShouldReturnCorrectCoordinatesForEachPosition(array $resourceSize, array $size, $position, $expectedCoordinates)
    {
        $this->assertEquals(
            $expectedCoordinates,
            $this->pixelMath->getPlacementCoordinates($resourceSize, $size, $position)
        );
    }

    /**
     * @return array
     */
    public function coordinatesProvider()
    {
        return [
            [
                'resourceSize' => ['width' => 10, 'height' => 5],
                'size' => ['width' => 100, 'height' => 100],
                'pos' => Imanee::IM_POS_TOP_LEFT,
                'expectedCoordinates' => [0, 0]
            ],
            [
                'resourceSize' => ['width' => 10, 'height' => 5],
                'size' => ['width' => 100, 'height' => 100],
                'pos' => Imanee::IM_POS_TOP_CENTER,
                'expectedCoordinates' => [45, 0]
            ],
            [
                'resourceSize' => ['width' => 10, 'height' => 5],
                'size' => ['width' => 100, 'height' => 100],
                'pos' => Imanee::IM_POS_TOP_RIGHT,
                'expectedCoordinates' => [90, 0]
            ],
            [
                'resourceSize' => ['width' => 10, 'height' => 5],
                'size' => ['width' => 100, 'height' => 100],
                'pos' => Imanee::IM_POS_MID_LEFT,
                'expectedCoordinates' => [0, 47]
            ],
            [
                'resourceSize' => ['width' => 10, 'height' => 5],
                'size' => ['width' => 100, 'height' => 100],
                'pos' => Imanee::IM_POS_MID_CENTER,
                'expectedCoordinates' => [45, 47]
            ],
            [
                'resourceSize' => ['width' => 10, 'height' => 5],
                'size' => ['width' => 100, 'height' => 100],
                'pos' => Imanee::IM_POS_MID_RIGHT,
                'expectedCoordinates' => [90, 47]
            ],
            [
                'resourceSize' => ['width' => 10, 'height' => 5],
                'size' => ['width' => 100, 'height' => 100],
                'pos' => Imanee::IM_POS_BOTTOM_LEFT,
                'expectedCoordinates' => [0, 95]
            ],
            [
                'resourceSize' => ['width' => 10, 'height' => 5],
                'size' => ['width' => 100, 'height' => 100],
                'pos' => Imanee::IM_POS_BOTTOM_CENTER,
                'expectedCoordinates' => [45, 95]
            ],
            [
                'resourceSize' => ['width' => 10, 'height' => 5],
                'size' => ['width' => 100, 'height' => 100],
                'pos' => Imanee::IM_POS_BOTTOM_RIGHT,
                'expectedCoordinates' => [90, 95]
            ],
        ];
    }
}
