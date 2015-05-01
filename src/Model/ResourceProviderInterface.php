<?php
/**
 * ResourceProvider Interface
 * Defines the common Image Resource methods for Imanee
 */

namespace Imanee\Model;

use Imanee\Drawer;
use Imanee\Exception\ImageNotFoundException;
use Imanee\Exception\EmptyImageException;

interface ResourceProviderInterface
{
    /**
     * Loads an existent image into the current image resource
     * @param string $image_path The path to an image to load
     * @return $this
     * @throws ImageNotFoundException
     */
    public function load($image_path);

    /**
     * Creates a new "blank" image for this image resource
     *
     * @param int $width  The width for the image
     * @param int $height The height for the image
     * @param string $background The background color
     */
    public function createNew($width, $height, $background = 'white');

    /**
     * @return mixed The image resource
     */
    public function getResource();

    /**
     * @return string the Mime Type if available
     */
    public function getMime();

    /**
     * Gets the currently defined image format
     *
     * @return string The image format
     */
    public function getFormat();

    /**
     * Sets the image format. Mandatory before outputting a new blank image
     *
     * @param string $format The image format, e.g: 'jpeg'
     */
    public function setFormat($format);

    /**
     * @return int Returns current image width
     */
    public function getWidth();

    /**
     * @return int Returns current image height
     */
    public function getHeight();

    /**
     * Resizes an image
     *
     * @param int  $width   The new width
     * @param int  $height  The new height
     * @param bool $bestfit When set to false, will force resize to specified dimensions. Default is true, which means
     * the resize will be proportional to fit in the provided dimensions, keeping the image always proportional.
     * @throws EmptyImageException
     */
    public function resize($width, $height, $bestfit = true);

    /**
     * Places a text on top of the current image resource using relative positioning and the provided Drawer object.
     * If fitWidth is provided, will calculate the correct font size to fit in the provided width.
     *
     * @param string $text           The text to be written.
     * @param int    $place_constant Where to place the image - one of the \Imanee:IM_POS constants
     * @param Drawer $drawer         The drawer object
     * @param int    $fitWidth       If provided and different than zero, will calculate a new font size
     * to fit text in the provided width
     */
    public function placeText($text, $place_constant, Drawer $drawer, $fitWidth = 0);

    /**
     * Writes text on the current image resource
     *
     * @param string $text
     * @param int    $coordX
     * @param int    $coordY
     * @param int    $angle
     * @param Drawer $drawer
     */
    public function annotate($text, $coordX, $coordY, $angle, Drawer $drawer);

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

    /**
     * Places an image on top of the current image resource using relative positioning.
     *
     * @param mixed  $image          The path for an image on the filesystem or an Imanee object
     * @param int    $place_constant Where to place the image - one of the \Imanee:IM_POS constants
     * @param int    $width          (optional) Width of the placed image, if resize is desirable
     * @param int    $height         (optional) Height of the placed image, if resize is desirable
     * @param int   $transparency    (optional) Transparency in percentage - 0 for fully opaque (default),
     * 0 for fully transparent.
     * @throws \Exception
     *
     * Note about transparency: change is made pixel per pixel, so using this will require more processing
     * depending on the image size.
     */
    public function placeImage($image, $place_constant, $width = 0, $height = 0, $transparency = 100);

    /**
     * Rotates the image resource in the given degrees
     *
     * @param float     $degrees Degrees to rotate the image. Negative values will rotate the image anti-clockwise
     * @param string $background Background to fill the empty spaces, default is transparent.
     * will render as black for jpg format (use png if you want it transparent)
     */
    public function rotate($degrees = 90.00, $background = 'transparent');

    /**
     * Crops a portion of the image
     *
     * @param int $width  The width
     * @param int $height The height
     * @param int $coordX The X coordinate
     * @param int $coordY The Y coordinate
     */
    public function crop($width, $height, $coordX, $coordY);

    /**
     * Creates a thumbnail of the current resource. If crop is true, the result will be a perfect fit thumbnail with the
     * given dimensions, cropped by the center. If crop is false, the thumbnail will use the best fit for the dimensions
     *
     * @param int  $width  Width of the thumbnail
     * @param int  $height Height of the thumbnail
     * @param bool $crop   When set to true, the thumbnail will be cropped from the center to match the given size
     */
    public function thumbnail($width, $height, $crop = false);

    /**
     * Outputs the image data as a string.
     *
     * @param string $format (optional) overwrites the current image format.
     * use it if you did not explicitly set the format on new images before calling output.
     * if no format was previously defined, it will use jpg
     *
     * @return string The image data as a string
     * @throws EmptyImageException
     */
    public function output($format = null);

    /**
     * Saves the image to disk. If the second param is provided, will try to compress the image using JPEG compression.
     *
     * The format will be decided based on the extension used for the filename. If, for instance,
     * a "img.png" is provided, the image will be saved as PNG and the compression will not take affect.
     *
     * @param string $file         The file path to save the image
     * @param int    $jpeg_quality (optional) the quality for JPEG files, 1 to 100 where 100 means no compression
     * (higher quality and bigger file)
     */
    public function write($file, $jpeg_quality = null);
}
