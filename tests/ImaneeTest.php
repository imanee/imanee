<?php

namespace Imanee\Tests;

use Imanee\Imanee;
use Imanee\Exception\UnsupportedMethodException;

class ImaneeTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Imanee */
    protected $model;

    public function setup()
    {
        $this->model = new Imanee();
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testConstructorShouldLoadDefaultObjects()
    {
        $this->assertInstanceOf('Imanee\Model\ImageResourceInterface', $this->model->getResource());
        $this->assertInstanceOf('Imanee\Drawer', $this->model->getDrawer());
    }

    public function testConstructorShouldSetCustomImageResource()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['load'])
            ->getMock();

        $imanee = new Imanee(null, $resource);
        $this->assertSame($resource, $imanee->getResource());
    }

    public function testShouldLoadImage()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['load'])
            ->getMock();

        $resource->expects($this->once())
            ->method('load')
            ->with('myimage.jpg');

        $imanee = new Imanee('myimage.jpg', $resource);
    }

    public function testShouldCreateNewImage()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['createNew'])
            ->getMock();

        $resource->expects($this->once())
            ->method('createNew')
            ->with(200, 200);

        $this->model->setResource($resource);
        $this->model->newImage(200, 200);
    }

    public function testShouldSetAndGetFormat()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['setFormat', 'getFormat'])
            ->getMock();

        $resource->expects($this->once())
            ->method('setFormat')
            ->with('jpeg');

        $resource->expects($this->once())
            ->method('getFormat')
            ->will($this->returnValue('jpeg'));

        $this->model->setResource($resource);
        $this->model->setFormat('jpeg');

        $this->assertEquals('jpeg', $this->model->getFormat());
    }

    public function testShouldResize()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['resize'])
            ->getMock();

        $resource->expects($this->once())
            ->method('resize')
            ->with(200, 200);

        $this->model->setResource($resource);
        $this->model->resize(200, 200);
    }

    public function testShouldNotResizeWhenImageIsSmallerThanBoxAndStretchIsFalse()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['resize'])
            ->getMock();

        $resource->expects($this->never())
            ->method('resize');

        $this->model->setResource($resource);
        $this->model->resize(1200, 1200, true, false);
    }

    public function testShouldRotate()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['rotate'])
            ->getMock();

        $resource->expects($this->once())
            ->method('rotate')
            ->with(90);

        $this->model->setResource($resource);
        $this->model->rotate(90);
    }

    public function testShouldCrop()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['crop'])
            ->getMock();

        $resource->expects($this->once())
            ->method('crop')
            ->with(50, 50, 200, 200);

        $this->model->setResource($resource);
        $this->model->crop(50, 50, 200, 200);
    }

    public function testShouldThumbnail()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['thumbnail'])
            ->getMock();

        $resource->expects($this->at(0))
            ->method('thumbnail')
            ->with(200, 200);

        $resource->expects($this->at(1))
            ->method('thumbnail')
            ->with(200, 200, true);

        $this->model->setResource($resource);
        $this->model->thumbnail(200, 200);
        $this->model->thumbnail(200, 200, true);
    }

    public function testShouldNotThumbnailWhenImageIsSmallerThanBoxAndStretchIsFalse()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['thumbnail'])
            ->getMock();

        $resource->expects($this->never())
            ->method('thumbnail');


        $this->model->setResource($resource);
        $this->model->thumbnail(1200, 1200, false, false);
    }

    public function testSetAndGetResource()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['crop'])
            ->getMock();

        $this->model->setResource($resource);

        $this->assertSame($resource, $this->model->getResource());
    }

    public function testSetAndGetDrawer()
    {
        $drawer = $this->getMockBuilder('Imanee\Drawer')
            ->getMock();

        $this->model->setDrawer($drawer);

        $this->assertSame($drawer, $this->model->getDrawer());
    }

    public function testShouldAnnotateImage()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['annotate'])
            ->getMock();

        $resource->expects($this->at(0))
            ->method('annotate')
            ->with('test', 10, 10);

        $drawer = $this->getMockBuilder('Imanee\Drawer')
            ->setMethods(['setFontSize'])
            ->getMock();

        $drawer->expects($this->once())
            ->method('setFontSize')
            ->with(20);

        $this->model->setDrawer($drawer);
        $this->model->setResource($resource);
        $this->model->annotate('test', 10, 10);
        $this->model->annotate('test', 10, 10, 20);
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedMethodException
     */
    public function testAnnotateShouldThrowExceptionIfNotSupported()
    {
        $dummy = $this->getMock('Imanee\Model\ImageResourceInterface');

        $this->model->setResource($dummy);
        $this->model->annotate('test', 10, 10);
    }

    public function testShouldPlaceText()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['annotate'])
            ->getMock();

        $resource->expects($this->at(0))
            ->method('annotate')
            ->with('testing');

        $drawer = $this->getMockBuilder('Imanee\Drawer')
            ->setMethods(['setFontSize'])
            ->getMock();

        $drawer->expects($this->any())
            ->method('setFontSize');

        $this->model->setDrawer($drawer);
        $this->model->setResource($resource);

        $this->model->placeText('testing');
        $this->model->placeText('testing', Imanee::IM_POS_MID_RIGHT, 10, 500);
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedMethodException
     */
    public function testPlaceTextShouldThrowExceptionIfNotSupported()
    {
        $dummy = $this->getMock('Imanee\Model\ImageResourceInterface');

        $this->model->setResource($dummy);
        $this->model->placeText('testing');
    }

    public function testShouldCompositeImage()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['compositeImage'])
            ->getMock();

        $resource->expects($this->once())
            ->method('compositeImage')
            ->with('test.jpg', 0, 0, 200, 200);

        $this->model->setResource($resource);
        $this->model->compositeImage('test.jpg', 0, 0, 200, 200);
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedMethodException
     */
    public function testCompositeImageShouldThrowExceptionIfNotSupported()
    {
        $dummy = $this->getMock('Imanee\Model\ImageResourceInterface');

        $this->model->setResource($dummy);
        $this->model->compositeImage('test.jpg', 0, 0, 200, 200);
    }

    public function testShouldPlaceImage()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['load', 'compositeImage'])
            ->getMock();

        $resource->expects($this->once())
            ->method('load')
            ->with('test.jpg');

        $resource->expects($this->once())
            ->method('compositeImage');

        $this->model->setResource($resource);
        $this->model->placeImage('test.jpg', Imanee::IM_POS_MID_CENTER);
    }

    public function testShouldResizeBeforePlaceImage()
    {
        $imanee = $this->getMockBuilder('Imanee\Imanee')
            ->setMethods(['resize'])
            ->getMock();

        $imanee->expects($this->once())
            ->method('resize')
            ->with(200, 200);

        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['compositeImage'])
            ->getMock();

        $resource->expects($this->once())
            ->method('compositeImage');

        $this->model->setResource($resource);
        $this->model->placeImage($imanee, Imanee::IM_POS_MID_CENTER, 200, 200);
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedMethodException
     */
    public function testPlaceImageShouldThrowExceptionIfNotSupported()
    {
        $dummy = $this->getMock('Imanee\Model\ImageResourceInterface');

        $this->model->setResource($dummy);
        $this->model->placeImage('test.jpg', Imanee::IM_POS_MID_CENTER);
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedFormatException
     */
    public function testPlaceImageShouldThrowExceptionIfWrongObject()
    {
        $dummy = $this->getMock('Imanee\Model\ImageResourceInterface');

        $this->model->placeImage($dummy, Imanee::IM_POS_MID_CENTER);
    }

    public function testShouldWatermark()
    {
        $mock = $this->getMockBuilder('Imanee\Imanee')
            ->setMethods(['placeImage'])
            ->getMock();

        $mock->expects($this->once())
            ->method('placeImage');

        $mock->watermark('test.jpg');
    }

    public function testDefaultFiltersAreLoaded()
    {
        $this->assertNotEmpty($this->model->getFilters());
    }

    public function testAddGetFilters()
    {
        $filter1 = $this->getMock('Imanee\Model\FilterInterface');
        $filter2 = $this->getMock('Imanee\Model\FilterInterface');

        $this->model
            ->addFilter($filter1)
            ->addFilter($filter2);

        $this->assertContainsOnlyInstancesOf('Imanee\Model\FilterInterface', $this->model->getFilters());
        $this->assertContains($filter1, $this->model->getFilters());
        $this->assertContains($filter2, $this->model->getFilters());
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedMethodException
     */
    public function testAddFilterShouldThrowExceptionIfNotSupported()
    {
        $dummy = $this->getMock('Imanee\Model\ImageResourceInterface');

        $this->model->setResource($dummy);
        $this->model->addFilter($this->getMock('Imanee\Model\FilterInterface'));
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedMethodException
     */
    public function testGetFiltersShouldThrowExceptionIfNotSupported()
    {
        $dummy = $this->getMock('Imanee\Model\ImageResourceInterface');

        $this->model->setResource($dummy);
        $this->model->getFilters();
    }

    public function testShouldApplyFilter()
    {
        $filter = $this->getMockBuilder('Imanee\Filter\Imagick\BWFilter')
            ->setMethods(['apply'])
            ->getMock();

        $filter->expects($this->once())
            ->method('apply');

        $resolver = $this->getMockBuilder('Imanee\FilterResolver')
            ->setMethods(['resolve'])
            ->getMock();

        $resolver->expects($this->once())
            ->method('resolve')
            ->will($this->returnValue($filter));

        $imanee = $this->getMockBuilder('Imanee\Imanee')
            ->setMethods(['getFilterResolver'])
            ->getMock();

        $imanee->expects($this->once())
            ->method('getFilterResolver')
            ->will($this->returnValue($resolver));

        $imanee->applyFilter('filter_bw');
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedMethodException
     */
    public function testApplyFilterShouldThrowExceptionIfNotSupported()
    {
        $dummy = $this->getMock('Imanee\Model\ImageResourceInterface');

        $this->model->setResource($dummy);
        $this->model->applyFilter('filter_bw');
    }

    /**
     * @expectedException Imanee\Exception\FilterNotFoundException
     */
    public function testApplyFilterShouldThrowExceptionIfFilterNotFound()
    {
        $this->model->applyFilter('filter_test');
    }

    public function testShouldAddAndGetFrames()
    {
        $this->model->addFrame('image01.jpg');
        $this->model->addFrame('image02.jpg');

        $this->assertNotEmpty($this->model->getFrames());

        $this->model->addFrames([
            'image03.jpg',
            'image04.jpg'
        ]);

        $this->assertCount(4, $this->model->getFrames());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveFrameShouldThrowExceptionIfOffsetNotExists()
    {
        $this->model->addFrame('image01.jpg');
        $this->model->addFrame('image02.jpg');

        $this->model->removeFrame(2);
    }

    public function testShouldAnimate()
    {
        $this->model->addFrame('image01.jpg');
        $this->model->addFrame('image02.jpg');

        $resource = $this->getMockBuilder('Imanee\ImageResource\ImagickResource')
            ->setMethods(['animate'])
            ->getMock();

        $resource->expects($this->once())
            ->method('animate');

        $this->model->setResource($resource);
        $this->model->animate();
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedMethodException
     */
    public function testAnimateShouldThrowExceptionIfNotSupported()
    {
        $dummy = $this->getMock('Imanee\Model\ImageResourceInterface');

        $this->model->setResource($dummy);
        $this->model->animate();
    }

    public function testShouldTextGen()
    {
        $resource = $this->getMockBuilder('Imanee\ImageResource\GDResource')
            ->setMethods(['getTextGeometry', 'createNew', 'setFormat', 'annotate'])
            ->getMock();

        $resource->expects($this->any())
            ->method('getTextGeometry');

        $resource->expects($this->once())
            ->method('annotate');

        $drawer = $this->getMockBuilder('Imanee\Drawer')
            ->setMethods(['getFontSize'])
            ->getMock();

        $drawer->expects($this->once())
            ->method('getFontSize');

        Imanee::textGen('Testing', $drawer, 'png', 'transparent', $resource);
    }
}
