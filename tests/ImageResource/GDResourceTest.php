<?php
namespace Imanee\Tests;

use Imanee\ImageResource\GDResource;

class GDResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Imanee\ImageResource\GDResource::load
     * @group imanee-33
     * @expectedException \Imanee\Exception\ImageNotFoundException
     * @expectedExceptionMessage File '/path/to/nowhere' not found. Are you sure this is the right path?
     */
    public function testExceptionIsThrownIfImagePathIsNotFound()
    {
        $imageResource = new GDResource();
        $imageResource->load('/path/to/nowhere');
    }

    public function imageTypeProvider()
    {
        return [
            ['jpg', 'image/jpeg'],
            ['gif', 'image/gif'],
            ['png', 'image/png'],
        ];
    }

    /**
     * @covers \Imanee\ImageResource\GDResource::load
     * @group imanee-33
     * @expectedException \Imanee\Exception\UnsupportedFormatException
     * @expectedExceptionMessage The format 'image/tiff' is not supported by this Resource.
     * @todo \Imanee\ImageResource\GDResource::load uses static method Imanee::getImageInfo
     */
    public function testLoadingUnsupportedImageThrowsException()
    {
        $file = __DIR__ . '/_files/imanee.tiff';
        $imageResource = new GDResource();
        $imageResource->load($file);
    }
    /**
     * @covers \Imanee\ImageResource\GDResource::loadColor
     * @group imanee-33
     * @dataProvider imageTypeProvider
     * @todo \Imanee\ImageResource\GDResource::load uses static method Imanee::getImageInfo
     */
    public function testLoadingImageCreatesAnImage($ext, $mime)
    {
        $file = __DIR__ . '/_files/imanee.' . $ext;
        $imageResource = new GDResource();
        $result = $imageResource->load($file);

        $this->assertSame($mime, $imageResource->mime);
        $this->assertSame($ext, $imageResource->format);

        $this->assertInstanceOf('\\Imanee\ImageResource\\GDResource', $result);
        $this->assertSame($imageResource, $result);
    }

    public function badColorProvider()
    {
        return [
            ['AABBCCDD'],
            ['foobar'],
            ['#1234567'],
        ];
    }

    /**
     * @covers \Imanee\ImageResource\GDResource::load
     * @group imanee-33
     * @dataProvider badColorProvider
     * @expectedException \Imanee\Exception\InvalidColorException
     */
    public function testLoadColourFailsWithBadInput($color)
    {
        $file = __DIR__ . '/_files/imanee.png';
        $gdResource = new GDResource();
        $gdResource->load($file);
        $this->assertFalse($gdResource->loadColor($color));
    }

    /**
     * @covers \Imanee\ImageResource\GDResource::output
     * @dataProvider imageProvider
     */
    public function testReturnTheImageInsteadOfPuttingItInTheBuffer($imageRelativePath)
    {
        $file = __DIR__ . $imageRelativePath;
        $gdResource = new GDResource();
        $gdResource->load($file);

        ob_start();
        $image = $gdResource->output();
        $buffer = ob_get_contents();
        ob_end_clean();

        $this->assertNotEmpty($image);
        $this->assertEmpty($buffer);
    }

    /**
     * @covers \Imanee\ImageResource\GDResource::output
     * @expectedException \Imanee\Exception\UnsupportedFormatException
     */
    public function testThrowErrorWhenFormatNotSupported()
    {
        $file = __DIR__ . '/_files/imanee.png';;
        $gdResource = new GDResource();
        $gdResource->load($file);

        $gdResource->output('wrongFormat');
    }

    public function imageProvider()
    {
        return [
            ['/_files/imanee.png'],
            ['/_files/imanee.jpg'],
            ['/_files/imanee.gif'],
        ];
    }

    /**
     * @expectedException Imanee\Exception\UnsupportedMethodException
     */
    public function testGetGifFramesShouldThrowExceptionAsUnsupported()
    {
        $imageResource = new GDResource();
        $imageResource->getGifFrames();
    }
}
