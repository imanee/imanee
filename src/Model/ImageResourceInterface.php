<?php
/**
 * ResourceProvider Interface
 * Defines the common Image Resource methods for Imanee
 */

namespace Imanee\Model;

use Imanee\Drawer;
use Imanee\Exception\ImageNotFoundException;
use Imanee\Exception\EmptyImageException;

interface ImageResourceInterface
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
     * @return mixed The image resource (Imagick object)
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

    /**
     * Applies a filter compatible with the current Resource Provider
     * @param FilterInterface $filter
     * @param array $options
     * @return mixed
     */
    public function applyFilter(FilterInterface $filter, array $options = []);
}
