<?php

namespace Imanee\Tests;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    protected $test_jpg;

    public function setup()
    {
        $this->test_jpg = __DIR__ . '/resources/img01.jpg';
    }

    public function testLoadJpg()
    {
        $image = new \Imanee\Image($this->test_jpg);

        $this->assertSame('image/jpeg', $image->mime);
    }

    public function testCreate()
    {
        $image = new \Imanee\Image();
        $image->createNew(100, 100);

        $this->assertNotNull($image->getResource());
        $this->assertSame(100, $image->width);
    }

    public function testSetFormat()
    {
        $image = new \Imanee\Image();
        $image->createNew(200,200);
        $image->setFormat('jpeg');

        $this->assertSame('jpeg', $image->getFormat());
    }

    public function testBlank()
    {
        $image = new \Imanee\Image();

        $this->assertTrue($image->isBlank());

        $image->createNew(50, 50);

        $this->assertFalse($image->isBlank());
    }

    public function testResize()
    {
        $image = new \Imanee\Image($this->test_jpg);
        $old_w = $image->width;
        $old_h = $image->height;

        $image->resize(20,20);

        $this->assertNotSame($old_w, $image->width);
        $this->assertNotSame($old_h, $image->height);
    }

    public function testOutput()
    {
        $image = new \Imanee\Image($this->test_jpg);
        $output = $image->output();

        $this->assertNotNull($output);
    }

    public function testSetBackground()
    {
        $image = new \Imanee\Image();
        $image->createNew(100, 100, 'black');

        $this->assertSame('black', $image->getBackground());
    }
}
