<?php
/**
 * SepiaFilter Tests
 */

namespace imanee\tests\Filter;


use Imanee\Filter\SepiaFilter;

class SepiaFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $model;

    public function setup()
    {
        $this->model = new SepiaFilter();
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testShouldReturnName()
    {
        $this->assertEquals('filter_sepia', $this->model->getName());
    }

    public function testShouldApplyFilter()
    {
        $imagick = $this->getMockBuilder('\Imagick')
            ->setMethods(['sepiaToneImage'])
            ->getMock();

        $imagick->expects($this->once())
            ->method('sepiaToneImage')
            ->with(90);

        $this->model->apply($imagick, [ 'threshold' => 90 ]);
    }
}
