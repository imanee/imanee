<?php
/**
 * Created by PhpStorm.
 * User: Rob
 * Date: 25-6-2015
 * Time: 19:52
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
        return array(
            array('#000000', 0, 0, 0),
            array('000000', 0, 0, 0),
            array('#FFFFFF', 255, 255, 255),
            array('FFFFFF', 255, 255, 255),
            array('#FF0000', 255, 0, 0),
            array('#008000', 0, 128, 0),
            array('#00000F', 0, 0, 15),

            array('black', 0, 0, 0), //000000
            array('white', 255, 255, 255), //FFFFFF
            array('grey', 190, 190, 190), //BEBEBE
            array('red', 254, 0, 0), //FE0000
            array('green', 0, 128, 1), //008001
            array('blue' , 0, 0 , 254), //0000FE
            array('purple', 129, 0, 127), //81007F
            array('pink', 255, 192, 203), //FFC0CB
            array('yellow', 255, 255, 0), //FFFF00
            array('orange', 254, 165, 0), //FEA500
            array('silver', 192, 192, 192), //C0C0C0
            array('lavender', 229, 230, 250), //E5E6FA
            array('salmon', 250, 128, 113), //FA8071
            array('magenta', 255, 0, 254), //FF00FE
            array('plum', 221, 160, 220), //DDA0DC
        );
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
        return array(
            array('lightGrey'),
            array('notsupported'),
            array('baddata'),
            array('abc'),
            array('12345'),
        );
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
