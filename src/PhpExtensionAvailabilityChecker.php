<?php

namespace Imanee;

/**
 * Checks if an extension is loaded
 */
class PhpExtensionAvailabilityChecker
{
    /**
     * @param string $name
     * @return bool
     */
    public function isLoaded($name)
    {
        return extension_loaded($name);
    }
}
