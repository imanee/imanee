<?php

namespace Imanee\Model;

use Exception;

/**
 * Composes an image from multiple layers.
 */
interface ImageComposableInterface
{
    /**
     * Places an image on top of the current image resource.
     *
     * @param mixed $image        The path for an image on the filesystem or an Imanee object.
     * @param int   $coordX       X coordinate to place the image.
     * @param int   $coordY       Y coordinate to place the image.
     * @param int   $width        Width of the placed image, if resize is desirable.
     * @param int   $height       Height of the placed image, if resize is desirable.
     * @param int   $transparency Transparency in percentage - 0 (opaque) to 100 (transparent).
     *
     * @return bool Returns true if successful.
     *
     * @throws Exception
     *
     * Transparency changes are made pixel per pixel, so using this will require more processing
     * depending on the image size.
     */
    public function compositeImage($image, $coordX, $coordY, $width = 0, $height = 0, $transparency = 0);
}
