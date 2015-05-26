<?php
/**
 * Resource Provider
 * Acts as a factory for resources based on which extensions are loaded
 */

namespace Imanee;


use Exception\ExtensionNotFoundException;
use Imanee\ImageResource\GDResource;
use Imanee\ImageResource\ImagickResource;

class ResourceProvider
{

    public function loadImageResource()
    {
        if (!extension_loaded('imagick')) {
            if (!extension_loaded('gd')) {
                throw new ExtensionNotFoundException(
                    "We couldn't detect Imagick or GD extensions.
                    You'll need to install one of these extensions in order to use Imanee."
                );
            }
        }
    }

    public function createImageResource()
    {
        if ($this->imaneeIsSupported()) {
            return new ImagickResource();
        }

        if ($this->gdIsLoaded()) {
            return new GDResource();
        }

        throw new ExtensionNotFoundException(
            "We couldn't detect Imagick or GD extensions.
                    You'll need to install one of these extensions in order to use Imanee."
        );
    }

    public function imaneeIsSupported()
    {
        return ($this->imagickIsLoaded() or $this->gdIsLoaded());
    }

    public function imagickIsLoaded()
    {
        return extension_loaded('imagick');
    }

    public function gdIsLoaded()
    {
        return extension_loaded('gd');
    }
}
