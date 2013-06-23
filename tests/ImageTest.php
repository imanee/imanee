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
        $image = \Imanee\Image::loadFromFile($this->test_jpg);

        $this->assertInstanceOf('Imanee\\Image\\Jpg', $image);
    }

    public function testCreateBlank()
    {
        $image = \Imanee\Image::createNew(100, 100);

        $this->assertSame(100, $image->width);
    }
}