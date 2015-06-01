<?php
/**
 * Resource Provider
 * Acts as a factory for resources based on which extensions are loaded
 */

namespace Imanee;

use Imanee\Exception\ExtensionNotFoundException;
use Imanee\ImageResource\GDResource;
use Imanee\ImageResource\ImagickResource;

class ResourceProvider
{
    /**
     * Checks for loaded extensions to create a suitable ImageResource, in case neither Imagick or GD are loaded
     * throws an exception
     * @return GDResource|ImagickResource
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
    public function imaneeIsSupported()
    {
        return ($this->imagickIsLoaded() or $this->gdIsLoaded());
    }

    /**
     * @return bool
     */
    public function imagickIsLoaded()
    {
        return extension_loaded('imagick');
    }

    /**
     * @return bool
     */
    public function gdIsLoaded()
    {
        return extension_loaded('gd');
    }
}
