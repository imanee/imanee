<?php
/**
 * ColorFilterTest
 */

namespace imanee\tests\Filter\Imagick;


use Imanee\Filter\Imagick\ColorFilter;

class ColorFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $model;

    public function setup()
    {
        $this->model = new ColorFilter();
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testShouldReturnName()
    {
        $this->assertEquals('filter_color', $this->model->getName());
    }

    public function testShouldApplyFilter()
    {
        $imagick = $this->getMockBuilder('\Imagick')
            ->setMethods(['colorizeImage'])
            ->getMock();

        $imagick->expects($this->once())
            ->method('colorizeImage')
            ->with('red', 1);

        $imanee = $this->getMockBuilder('Imanee\Imanee')
            ->setMethods(['getResource'])
            ->getMock();

        $imresource = $this->getMockBuilder('Imanee\ImageResource\ImagickResource')
            ->setMethods(['getResource'])
            ->getMock();

        $imanee->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($imresource));

        $imresource->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($imagick));

        $this->model->apply($imanee, [ 'color' => 'red' ]);
    }
}
