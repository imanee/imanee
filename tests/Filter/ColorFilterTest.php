<?php
/**
 * ColorFilterTest
 */

namespace imanee\tests\Filter;


use Imanee\Filter\ColorFilter;

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

        $this->model->apply($imagick, [ 'color' => 'red' ]);
    }
}
