<?php
/**
 * GDPixel Tests
 */

namespace Imanee\Tests;

use Imanee\ImageResource\GDPixel;

class GDPixelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Check that colours can be created from appropriate color strings
     * @dataProvider colorTestValues
     */
    public function testCreateGDPixelByColorString($hexColor, $r, $g, $b)
    {
        $gdPixel = new GDPixel($hexColor);
        $this->assertEquals($r, $gdPixel->channelR, 'red value incorrect for ' . $hexColor);
        $this->assertEquals($g, $gdPixel->channelG, 'green value incorrect for ' . $hexColor);
        $this->assertEquals($b, $gdPixel->channelB, 'blue value incorrect for ' . $hexColor);
    }

    public function colorTestValues()
    {
        return [
            ['#000000', 0, 0, 0],
            ['000000', 0, 0, 0],
            ['#FFFFFF', 255, 255, 255],
            ['FFFFFF', 255, 255, 255],
            ['#FF0000', 255, 0, 0],
            ['#008000', 0, 128, 0],
            ['#00000F', 0, 0, 15],

            ['black', 0, 0, 0], //000000
            ['white', 255, 255, 255], //FFFFFF
            ['grey', 190, 190, 190], //BEBEBE
            ['red', 254, 0, 0], //FE0000
            ['green', 0, 128, 1], //008001
            ['blue' , 0, 0 , 254], //0000FE
            ['purple', 129, 0, 127], //81007F
            ['pink', 255, 192, 203], //FFC0CB
            ['yellow', 255, 255, 0], //FFFF00
            ['orange', 254, 165, 0], //FEA500
            ['silver', 192, 192, 192], //C0C0C0
            ['lavender', 229, 230, 250], //E5E6FA
            ['salmon', 250, 128, 113], //FA8071
            ['magenta', 255, 0, 254], //FF00FE
            ['plum', 221, 160, 220], //DDA0DC
        ];
    }

    /**
     * Check that colours can be created from appropriate color strings
     * @dataProvider badColorTestValues
     * @expectedException \Imanee\Exception\InvalidColorException
     */
    public function testBadCreateGDPixelByColorString($hexColor)
    {
        new GDPixel($hexColor);
        $this->fail('InvalidColorException expected');
    }

    public function badColorTestValues()
    {
        return [
            ['lightGrey'],
            ['notsupported'],
            ['baddata'],
            ['abc'],
            ['12345'],
        ];
    }

    /**
     * Check that the transparent color can be created with static load command
     */
    public function testCreateGDPixelByLoadTransparentColor()
    {
        $resource = imagecreate(1, 1);
        $GDPixel = GDPixel::load('transparent', $resource);
        $this->assertsame(0, $GDPixel);
    }

    /**
     * Check that colours can be created with static load command
     * @dataProvider colorTestValues
     */
    public function testCreateGDPixelByStaticLoadColorString($hexColor, $r, $g, $b)
    {
        $gdPixel = GDPixel::load($hexColor, imagecreate(1, 1));
        $this->assertNotSame(false, $gdPixel);
    }

    /**
     * Check that colours can be created from appropriate color strings
     * @dataProvider badColorTestValues
     * @expectedException \Imanee\Exception\InvalidColorException
     */
    public function testBadCreateGDPixelByStaticLoadColorString($hexColor)
    {
        new GDPixel($hexColor);
        $this->fail('InvalidColorException expected');
    }
}
