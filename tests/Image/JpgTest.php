<?php
/**
 * JPG tests
 */
include __DIR__ . '/../../vendor/autoload.php';

class JpgTest extends PHPUnit_Framework_TestCase {

    protected $test_jpg;

    public function setup()
    {
        $this->test_jpg = __DIR__ . '/../resources/img01.jpg';
    }

    public function testConstruct()
    {
        $image = new \Imanee\Image\Jpg($this->test_jpg);

        $this->assertNotNull($image->getResource());
        $this->assertSame($image->mime, 'image/jpeg');
    }

}
