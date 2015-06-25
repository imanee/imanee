<?php

namespace Imanee;

use Imanee\Exception\ExtensionNotFoundException;
use Imanee\ImageResource\GDResource;
use Imanee\ImageResource\ImagickResource;

/**
 * Factory for resources based on which extensions are loaded.
 */
class ResourceProvider
{
    /**
     * @var PhpExtensionAvailabilityChecker;
     */
    private $PhpExtensionAvailabilityChecker;

    /**
     * @param PhpExtensionAvailabilityChecker $PhpExtensionAvailabilityChecker
     */
    public function __construct(PhpExtensionAvailabilityChecker $PhpExtensionAvailabilityChecker)
    {
        $this->PhpExtensionAvailabilityChecker = $PhpExtensionAvailabilityChecker;
    }

    /**
     * Checks for loaded extensions to create a suitable ImageResource, in case neither Imagick or
     * GD are loaded throws an exception.
     *
     * @return GDResource|ImagickResource
     * 
     * @throws ExtensionNotFoundException
     */
    public function createImageResource()
    {
        if ($this->imagickIsLoaded()) {
            return new ImagickResource();
        }

        if ($this->gdIsLoaded()) {
            return new GDResource();
        }

        throw new ExtensionNotFoundException(
            "We couldn't detect neither Imagick or GD extensions.
            You'll need to install one of these extensions in order to use Imanee.
            Check this link for more info: http://imanee.io/#requirements"
        );
    }

    /**
     * @return bool
     */
    private function imagickIsLoaded()
    {
        return $this->PhpExtensionAvailabilityChecker->isLoaded('imagick');
    }

    /**
     * @return bool
     */
    private function gdIsLoaded()
    {
        return $this->PhpExtensionAvailabilityChecker->isLoaded('gd');
    }
}
