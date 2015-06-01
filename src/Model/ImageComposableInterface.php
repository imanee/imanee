<?php
/**
 * Image: Composable
 * Classes which implement this interface should be able to handle image composition with multiple layers
 */

namespace Imanee\Model;


interface ImageComposableInterface
{
    /**
     * Places an image on top of the current image resource.
     *
     * @param mixed $image        The path for an image on the filesystem or an Imanee object
     * @param int   $coordX       X coord to place the image
     * @param int   $coordY       Y coord to place the image
     * @param int   $width        (optional) Width of the placed image, if resize is desirable
     * @param int   $height       (optional) Height of the placed image, if resize is desirable
     * @param int   $transparency (optional) Transparency in percentage - 0 for fully opaque (default),
     * 100 for fully transparent.
     *
     * @throws \Exception
     *
     * Note about transparency: the change is made pixel per pixel, so using this will require more processing
     * depending on the image size.
     */
    public function compositeImage($image, $coordX, $coordY, $width = 0, $height = 0, $transparency = 0);
}
