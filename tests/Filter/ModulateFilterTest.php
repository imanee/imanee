<?php
/**
 * ModulateFilter Tests
 */

namespace imanee\tests\Filter;


use Imanee\Filter\ModulateFilter;

class ModulateFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $model;

    public function setup()
    {
        $this->model = new ModulateFilter();
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testShouldReturnName()
    {
        $this->assertEquals('filter_modulate', $this->model->getName());
    }

    public function testShouldApplyFilter()
    {
        $imagick = $this->getMockBuilder('\Imagick')
            ->setMethods(['modulateImage'])
            ->getMock();

        $imagick->expects($this->once())
            ->method('modulateImage')
            ->with(90, 50, 90);

        $this->model->apply($imagick, [
            'brightness' => 90,
            'saturation' => 50,
            'hue'        => 90,
        ]);
    }
}
