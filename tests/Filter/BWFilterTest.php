<?php
/**
 * BWFilter Tests
 */

namespace imanee\tests\Filter;


use Imanee\Filter\BWFilter;

class BWFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $model;

    public function setup()
    {
        $this->model = new BWFilter();
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testShouldReturnName()
    {
        $this->assertEquals('filter_bw', $this->model->getName());
    }

    public function testShouldApplyFilter()
    {
        $imagick = $this->getMockBuilder('\Imagick')
            ->setMethods(['modulateImage'])
            ->getMock();

        $imagick->expects($this->once())
            ->method('modulateImage')
            ->with(100, 0, 100);

        $this->model->apply($imagick);
    }
}
