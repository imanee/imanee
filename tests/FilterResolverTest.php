<?php
/**
 * FilterResolver tests
 */

namespace imanee\tests;


use Imanee\FilterResolver;

class FilterResolverTest extends \PHPUnit_Framework_TestCase
{
    protected $model;

    public function setup()
    {
        $this->model = new FilterResolver();
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testShouldAddAndResolveFilter()
    {
        $filter = $this->getMockBuilder('Imanee\Filter\BWFilter')
            ->setMethods(['getName'])
            ->getMock();

        $filter->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('filter_bw'));

        $this->model->addFilter($filter);

        $returnedFilter = $this->model->resolve('filter_bw');

        $this->assertSame($filter, $returnedFilter);
    }

    public function testReturnFalseIfNoFilterIsFound()
    {
        $filter = $this->model->resolve('filter_something');

        $this->assertFalse($filter);
    }

    public function testShouldGetFilters()
    {
        $filter1 = $this->getMockBuilder('Imanee\Filter\BWFilter')
            ->getMock();

        $filter2 = $this->getMockBuilder('Imanee\Filter\ColorFilter')
            ->getMock();

        $this->model->addFilter($filter1);
        $this->model->addFilter($filter2);

        $this->assertNotNull($this->model->getFilters());
        $this->assertTrue(is_array($this->model->getFilters()));
        $this->assertCount(2, $this->model->getFilters());
        $this->assertContainsOnlyInstancesOf('Imanee\FilterInterface', $this->model->getFilters());
    }
}
