<?php
/**
 * Image Test
 */

include __DIR__ . '/../vendor/autoload.php';

class ImageTest extends PHPUnit_Framework_TestCase{

    protected $test_jpg;

    public function setup()
    {
        $this->test_jpg = __DIR__ . '/resources/img01.jpg';
    }

    public function testCreateJpg()
    {
        $image = new \Imanee\Image($this->test_jpg);

        $this->assertSame('image/jpeg', $image->mime);
    }

    public function testCreateBlank()
    {
        $image = new \Imanee\Image(null, 100, 100);

        $this->assertSame(100, $image->width);
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
}