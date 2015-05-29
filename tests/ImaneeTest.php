<?php

namespace Imanee\Tests;

use Imanee\Imanee;

class ImaneeTest extends \PHPUnit_Framework_TestCase
{
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
}
