<?php
/**
 * Imanee Image Class
 * @author Erika Heidi<erika@erikaheidi.com>
 */
namespace Imanee;

use Imanee\Exception\EmptyImageException;
use Imanee\Exception\ImageNotFoundException;

/**
 * Class Image
 *
 * Works as a wrapper for the ImageMagick objects and provides convenient methods for working with them
 *
 * @package Imanee
 */
class Image {

    /** @var \Imagick the image resource */
    private $resource;

    /** @var string the path to the current image resource if loaded from file */
    public  $image_path;

    /** @var string the image mime type */
    public  $mime;

    /** @var  int the image width */
    public  $width;

    /** @var int the image height */
	public  $height;

    /** @var string the image background if defined */
    public  $background;

    /**
     * Creates a new Image object
     * @param string $image_path If the image path is provided, the load method will be called
     */
    public function __construct($image_path = null)
    {
        $this->resource = new \Imagick();

        if ($image_path !== null) {
            $this->load($image_path);
        }

    }

    /**
     * @inheritdoc
     */
    public function __clone()
    {
        $this->resource = clone $this->resource;
    }

    /**
     * Creates a new "blank" image for this image resource
     *
     * @param int $width  The width for the image
     * @param int $height The height for the image
     * @param string $background The background color - CSS values can be used
     */
    public function createNew($width, $height, $background = 'white')
    {
        $this->width      = $width;
        $this->height     = $height;
        $this->background = $background;

        $this->resource->newImage($width, $height, new \ImagickPixel($background));
    }

    /**
     * Loads an existent image into the current image resource
     * @return $this
     * @throws Exception\ImageNotFoundException
     */
    public function load($image_path)
	{
        if (!is_file($image_path))
            throw new ImageNotFoundException("File not Found.");

        $this->image_path = $image_path;
        $this->loadImageInfo();
        $this->resource = new \Imagick($this->image_path);

        return $this;
	}

    /**
     * @return \Imagick The imagick resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Resizes an image
     *
     * @param int $width  The new width
     * @param int $height The new height
     * @throws Exception\EmptyImageException
     */
    public function resize($width, $height)
    {
        if ($this->isBlank())
            throw new EmptyImageException("You are trying to resize an empty image.");

        $this->resource->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        $newsize = $this->resource->getImageGeometry();

        $this->width  = $newsize['width'];
        $this->height = $newsize['height'];
    }

    /**
     * Loads information about the current image resource
     *
     * @throws Exception\ImageNotFoundException
     */
    public function loadImageInfo()
	{
        if (!is_file($this->image_path))
            throw new ImageNotFoundException("File not Found.");

        $info = getimagesize($this->image_path);

        $this->mime   = $info['mime'];
        $this->width  = $info[0];
        $this->height = $info[1];

	}

    /**
     * Sets the image format. Mandatory before outputting a new blank image
     *
     * @param string $format The image format, e.g: 'jpeg'
     */
    public function setFormat($format)
    {
        $this->resource->setimageformat($format);
    }

    /**
     * Gets the currently defined image format
     *
     * @return string The image format
     */
    public function getFormat()
    {
        return $this->resource->getimageformat();
    }

    /**
     * Returns a previously defined (e.g. when creating a new image) background color
     *
     * @return string The string previously used to define the background
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * Writes text on the current image resource
     *
     * @param string $text
     * @param int    $coordX
     * @param int    $coordY
     * @param int    $angle
     * @param Drawer $drawer
     */
    public function annotate($text, $coordX, $coordY, $angle, Drawer $drawer)
    {
        $this->resource->annotateimage($drawer->getDrawer(),$coordX, $coordY, $angle, $text );
    }

    /**
     * Gets the size of a text, given the text and the \Imanee\Drawer object
     *
     * @param string $text   The text
     * @param Drawer $drawer The Drawer object
     * @return array
     */
    public function getTextGeometry($text, Drawer $drawer)
    {
        $metrics = $this->resource->queryFontMetrics($drawer->getDrawer(), $text);

        return array(
            'width'  => $metrics['textWidth'],
            'height' => $metrics['textHeight'],
        );
    }

    /**
     * Places a text on top of the current image resource using relative positioning and the provided Drawer object
     *
     * @param string $text           The text to be written.
     * @param int    $place_constant Where to place the image - one of the \Imanee:IM_POS constants
     * @param Drawer $drawer         The drawer object
     */
    public function placeText($text, $place_constant, Drawer $drawer)
    {
        $textsize = $this->getTextGeometry($text, $drawer);
        list($coordX, $coordY) = $this->getPlacementCoordinates($textsize, $place_constant);

        $this->resource->annotateimage($drawer->getDrawer(),$coordX, $coordY, 0, $text);
    }

    /**
     * Places an image on top of the current image resource.
     *
     * @param mixed $image   The path for an image on the filesystem or an Imanee object
     * @param int   $coordX  X coord to place the image
     * @param int   $coordY  Y coord to place the image
     * @param int   $width   (optional) Width of the placed image, if resize is desirable
     * @param int   $height  (optional) Height of the placed image, if resize is desirable
     * @param int   $opacity (optional) Opacity in percentage - 100 for fully opaque (default), 0 for fully transparent.
     *
     * Note about opacity: the opacity is changed pixel per pixel, so using this will require more processing depending on the image size.
     * @throws \Exception
     */
    public function compositeImage($image, $coordX, $coordY, $width = 0, $height = 0, $opacity = 100)
    {
        if (!is_object($image)) {
            $img = new \Imagick($image);
        } else {
            if (! ($image instanceof \Imanee\Imanee) )
                throw new \Exception('Object not supported. It must be an instance of Imanee');

            $img = $image->getIMResource();
        }

        if ($width AND $height) {
            $img->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        }

        if ($opacity != 100) {
            $this->setOpacity($img, $opacity);
        }

        $this->resource->compositeimage($img, \Imagick::COMPOSITE_OVER, $coordX, $coordY);
    }

    /**
     * Places an image on top of the current image resource using relative positioning.
     *
     * @param mixed  $image          The path for an image on the filesystem or an Imanee object
     * @param int    $place_constant Where to place the image - one of the \Imanee:IM_POS constants
     * @param int    $width          (optional) Width of the placed image, if resize is desirable
     * @param int    $height         (optional) Height of the placed image, if resize is desirable
     * @param int    $opacity        (optional) Opacity in percentage - 100 for fully opaque (default), 0 for fully transparent.
     *
     * Note about opacity: the opacity is changed pixel per pixel, so using this will require more processing depending on the image size.
     */
    public function placeImage($image, $place_constant, $width = 0, $height = 0, $opacity = 100)
    {
        if (!is_object($image)) {
            $img = new \Imagick($image);
        } else {
            if (! ($image instanceof \Imanee\Imanee) )
                throw new \Exception('Object not supported. It must be an instance of Imanee');

            $img = $image->getIMResource();
        }

        if ($width AND $height) {
            $img->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        }

        list($coordX, $coordY) = $this->getPlacementCoordinates($img->getimagegeometry(), $place_constant);
        $this->compositeImage($image, $coordX, $coordY, 0, 0, $opacity);
    }

    /**
     * Rotates the image resource in the given degrees
     *
     * @param float     $degrees Degrees to rotate the image. Negative values will rotate the image anti-clockwise
     * @param string $background Background to fill the empty spaces, default is transparent - will render as black for jpg format (use png if you want it transparent)
     */
    public function rotate($degrees = 90, $background = 'transparent')
    {
        $this->resource->rotateimage(new \ImagickPixel($background), $degrees);
    }

    /**
     * Crops a portion of the image
     *
     * @param int $width  The width
     * @param int $height The height
     * @param int $coordX The X coordinate
     * @param int $coordY The Y coordinate
     */
    public function crop($width, $height, $coordX, $coordY)
    {
        $this->width = $width;
        $this->height = $height;

        $this->resource->cropimage($width, $height, $coordX, $coordY);
    }

    /**
     * Creates a thumbnail of the current resource. If crop is true, the result will be a perfect fit thumbnail with the
     * given dimensions, cropped by the center. If crop is false, the thumbnail will use the best fit for the dimensions.
     *
     * @param int  $width  Width of the thumbnail
     * @param int  $height Height of the thumbnail
     * @param bool $crop   When set to true, the thumbnail will be cropped from the center to match the given size
     */
    public function thumbnail($width, $height, $crop = false)
    {
        if ($crop) {
            $this->resource->cropthumbnailimage($width, $height);
        } else {
            $this->resource->thumbnailimage($width, $height, true);
        }
    }

    /**
     * Outputs the image data as a string.
     *
     * @param string $format (optional) overwrites the current image format.
     *  use it if you didn't explicitly set the format on new images before calling output
     *
     * @return string The image data as a string
     * @throws Exception\EmptyImageException
     */
    public function output($format = null)
    {
        if ($this->isBlank())
            throw new EmptyImageException("You are trying to output an empty image.");

        if ($format !== null) {
            $this->resource->setimageformat($format);
        }

        return $this->resource->getImageBlob();
    }

    /**
     * Saves the image to disk. If the second param is provided, will try to compress the image using JPEG compression.
     *
     * The format will be decided based on the extension used for the filename. If, for instance, a "img.png" is provided,
     * the image will be saved as PNG and the compression will not take affect.
     *
     * @param string $file         The file path to save the image
     * @param int    $jpeg_quality (optional) the quality for JPEG files, 1 to 100 where 100 means no compression (higher quality and bigger file)
     */
    public function write($file, $jpeg_quality = 0)
    {
        if ($jpeg_quality) {
            $this->resource->setimagecompression(\Imagick::COMPRESSION_JPEG);
            $this->resource->setimagecompressionquality($jpeg_quality);
        }
        $this->resource->writeimage($file);
    }

    /**
     * Checks if the current resource is empty
     * @return bool Returns true if the resource is empty (no file was loaded or no new image created)
     */
    public function isBlank()
    {
        return !$this->width;
    }

    /**
     * Gets the coordinates for a placement relative to the current image resource using the IM_POS constants
     * See \Imanee\Image::placeImage for usage example
     *
     * @param array $resource_size  an array with the keys 'width' and 'height' from the image to be placed
     * @param int   $place_constant one of the \Imanee::IM_POS constant (default is IM_POS_TOP_LEFT)
     * @return array Returns an array with the first position representing the X coordinate and the second position representing the Y coordinate for placing the image
     */
    public function getPlacementCoordinates($resource_size = [], $place_constant = Imanee::IM_POS_TOP_LEFT)
    {
        $x = 0;
        $y = 0;

        $size = $this->resource->getImageGeometry();

        switch ($place_constant) {

            case Imanee::IM_POS_TOP_CENTER:
                $x = ($size['width'] / 2) - ($resource_size['width'] / 2);
                break;

            case Imanee::IM_POS_TOP_RIGHT:
                $x = ($size['width']) - ($resource_size['width']);
                break;

            case Imanee::IM_POS_MID_LEFT:
                $y = ($size['height'] / 2) - ($resource_size['height'] / 2);
                break;

            case Imanee::IM_POS_MID_CENTER:
                $x = ($size['width'] / 2) - ($resource_size['width'] / 2);
                $y = ($size['height'] / 2) - ($resource_size['height'] / 2);
                break;

            case Imanee::IM_POS_MID_RIGHT:
                $x = ($size['width']) - ($resource_size['width']);
                $y = ($size['height'] / 2) - ($resource_size['height'] / 2);
                break;

            case Imanee::IM_POS_BOTTOM_LEFT:
                $y = ($size['height']) - ($resource_size['height']);
                break;

            case Imanee::IM_POS_BOTTOM_CENTER:
                $x = ($size['width'] / 2) - ($resource_size['width'] / 2);
                $y = ($size['height']) - ($resource_size['height']);
                break;

            case Imanee::IM_POS_BOTTOM_RIGHT:
                $x = ($size['width']) - ($resource_size['width']);
                $y = ($size['height']) - ($resource_size['height']);
                break;
        }

        return [$x, $y];
    }

    /**
     * Manually sets the opacity pixel per pixel.
     *
     * This method properly sets the opacity on a png with transparency, by iterating pixel per pixel. It's a substitute
     * for the Imagick::setImageOpacity, since it doesn't handle well transparent backgrounds.
     *
     * @param \Imagick  $resource The imagick resource to set opacity
     * @param int       $opacity  The opacity percentage, 0 to 100 - where 100 is fully opaque
     * @return \Imagick Returns the Imagick object with changed opacity
     */
    public function setOpacity(\Imagick $resource, $opacity)
    {
        $alpha = $opacity / 100;

        if ($alpha >= 1) {
            return true;
        }

        $rows = $resource->getPixelIterator();

        foreach ($rows as $cols) {
            foreach($cols as $pixel) {

                $current = $pixel->getColorValue(\Imagick::COLOR_ALPHA);

                $pixel->setColorValue(\Imagick::COLOR_ALPHA, (($current - $alpha > 0) ? ($current - $alpha) : (0)));

                $rows->syncIterator();
            }
        }

        return true;
    }

}