<?php

namespace Imanee\Tests;

use Imanee\ResourceProvider;
use PHPUnit_Framework_TestCase;
use Mockery;

class ResourceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    private $PhpExtensionAvailabilityChecker;

    /**
     * @var ResourceProvider
     */
    private $resourceProvider;

    public function setUp()
    {
        $this->PhpExtensionAvailabilityChecker = Mockery::mock('Imanee\PhpExtensionAvailabilityChecker');
        $this->resourceProvider = new ResourceProvider($this->PhpExtensionAvailabilityChecker);
    }

    /**
     * @expectedException Imanee\Exception\ExtensionNotFoundException
     */
    public function testshouldFailIfNoUsableExtensionIsAvailable()
    {
        $this->PhpExtensionAvailabilityChecker
            ->shouldReceive('isLoaded')
            ->with('imagick')
            ->andReturn(false);

        $this->PhpExtensionAvailabilityChecker
            ->shouldReceive('isLoaded')
            ->with('gd')
            ->andReturn(false);

        $this->resourceProvider->createImageResource();
    }

    public function testShouldFailReturnGdResourceIfImagickIsNotAvailable()
    {
        $this->PhpExtensionAvailabilityChecker
            ->shouldReceive('isLoaded')
            ->with('imagick')
            ->andReturn(false);
        $this->PhpExtensionAvailabilityChecker
            ->shouldReceive('isLoaded')
            ->with('gd')
            ->andReturn(true);

        $this->assertInstanceOf('Imanee\ImageResource\GDResource', $this->resourceProvider->createImageResource());

    }

    public function testShouldReturnImagickResource()
    {
        $this->PhpExtensionAvailabilityChecker
            ->shouldReceive('isLoaded')
            ->with('imagick')
            ->andReturn(true);

        $this->assertInstanceOf('Imanee\ImageResource\ImagickResource', $this->resourceProvider->createImageResource());
    }
}
