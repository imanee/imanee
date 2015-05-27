<?php
/**
 * Default Imagick Provider for Imanee using the Imagick Extension
 */

namespace Imanee\ImageResource;

use Imanee\Exception\EmptyImageException;
use Imanee\Exception\ImageNotFoundException;
use Imanee\Model\FilterInterface;
use Imanee\Model\ImageResourceInterface;
use Imanee\Imanee;
use Imanee\Drawer;
use Imanee\Model\ImageAnimatableInterface;
use Imanee\Model\ImageComposableInterface;
use Imanee\Model\ImageWritableInterface;
use Imanee\PixelMath;

class ImagickResource implements
    ImageResourceInterface,
    ImageWritableInterface,
    ImageComposableInterface,
    ImageAnimatableInterface
{
    /** @var \Imagick the image resource */
    private $resource;

    /** @var string the path to the current image resource if loaded from file */
    public $imagePath;

    /** @var string the image mime type */
    public $mime;

    /** @var  int the image width */
    public $width;

    /** @var int the image height */
    public $height;

    /** @var string the image background if defined */
    public $background;

    public function __construct()
    {
        $this->resource = new \Imagick();
    }

    /**
     * @inheritdoc
     */
    public function __clone()
    {
        $this->resource = clone $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function load($imagePath)
    {
        if (!is_file($imagePath)) {
            throw new ImageNotFoundException(
                sprintf("File '%s' not found. Are you sure this is the right path?", $imagePath)
            );
        }

        $info = Imanee::getImageInfo($imagePath);
        $this->mime = strtolower($info['mime']);
        $this->width = $info['width'];
        $this->height = $info['height'];
        $this->imagePath = $imagePath;

        $this->resource = new \Imagick($this->imagePath);
    }

    /**
     * {@inheritdoc}
     */
    public function createNew($width, $height, $background = 'white')
    {
        $this->width      = $width;
        $this->height     = $height;
        $this->background = $background;

        $this->resource->newImage($width, $height, new \ImagickPixel($background));
    }


    /**
     * @return \Imagick The imagick resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Sets the current Imagick resource and updates the Image info
     * @param \Imagick $resource
     */
    public function setResource(\Imagick $resource)
    {
        $this->resource = $resource;
        $this->updateResourceDimensions();
    }

    /**
     * {@inheritdoc}
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * {@inheritdoc}
     */
    public function resize($width, $height, $bestfit = true)
    {
        if ($this->isBlank()) {
            throw new EmptyImageException("You are trying to resize an empty image.");
        }

        $this->resource->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, $bestfit);

        $this->updateResourceDimensions();
    }

    /**
     * Updates the computed width and height for the current Imagick object
     */
    public function updateResourceDimensions()
    {
        $newsize = $this->resource->getImageGeometry();
        $this->width  = $newsize['width'];
        $this->height = $newsize['height'];
    }

    /**
     * {@inheritdoc}
     */
    public function setFormat($format)
    {
        $this->resource->setImageFormat($format);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return $this->resource->getImageFormat();
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
     * {@inheritdoc}
     */
    public function annotate($text, $coordX, $coordY, $angle, Drawer $drawer)
    {
        $this->resource->annotateImage($drawer->getDrawer(), $coordX, $coordY, $angle, $text);
    }

    /**
     * {@inheritdoc}
     */
    public function getTextGeometry($text, Drawer $drawer)
    {
        $metrics = $this->resource->queryFontMetrics($drawer->getDrawer(), $text);

        return [
            'width'  => $metrics['textWidth'],
            'height' => $metrics['textHeight'],
        ];
    }

    /**
     * Adjusts the font size of the Drawer object to fit a text in the desired width
     * @param $text
     * @param Drawer $drawer
     * @param $width
     * @return int
     */
    public function adjustFontSize($text, Drawer $drawer, $width)
    {
        $fontSize = 0;
        $metrics['width'] = 0;

        while ($metrics['width'] <= $width) {
            $drawer->setFontSize($fontSize);
            $metrics = $this->getTextGeometry($text, $drawer);
            $fontSize++;
        }

        return $drawer;
    }

    /**
     * {@inheritdoc}
     */
    public function placeText($text, $place_constant, Drawer $drawer, $fitWidth = 0)
    {
        if ($fitWidth > 0) {
            $drawer = $this->adjustFontSize($text, $drawer, $fitWidth);
        }

        $textsize = $this->getTextGeometry($text, $drawer);

        list($coordX, $coordY) = PixelMath::getPlacementCoordinates(
            $textsize,
            $this->resource->getImageGeometry(),
            $place_constant
        );

        $this->resource->annotateImage($drawer->getDrawer(), $coordX, $coordY + $drawer->getFontSize(), 0, $text);
    }

    /**
     * {@inheritdoc}
     */
    public function compositeImage($image, $coordX, $coordY, $width = 0, $height = 0, $transparency = 0)
    {
        if (!is_object($image)) {
            $img = new \Imagick($image);
        } else {
            if (! ($image instanceof \Imanee\Imanee)) {
                throw new \Exception('Object not supported. It must be an instance of Imanee');
            }

            $img = $image->getIMResource();
        }

        if ($width and $height) {
            $img->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        }

        if ($transparency > 0) {
            $this->setOpacity($img, $transparency);
        }

        $this->resource->compositeImage($img, \Imagick::COMPOSITE_OVER, $coordX, $coordY);
    }

    /**
     * {@inheritdoc}
     */
    public function rotate($degrees = 90.00, $background = 'transparent')
    {
        $this->resource->rotateimage(new \ImagickPixel($background), $degrees);
    }

    /**
     * {@inheritdoc}
     */
    public function crop($width, $height, $coordX, $coordY)
    {
        $this->width = $width;
        $this->height = $height;

        $this->resource->cropImage($width, $height, $coordX, $coordY);
    }

    /**
     * {@inheritdoc}
     */
    public function thumbnail($width, $height, $crop = false)
    {
        if ($crop) {
            $this->resource->cropThumbnailImage($width, $height);
        } else {
            $this->resource->thumbnailImage($width, $height, true);
        }

        $newsize = $this->resource->getImageGeometry();
        $this->width  = $newsize['width'];
        $this->height = $newsize['height'];
    }

    /**
     * {@inheritdoc}
     */
    public function output($format = null)
    {
        if ($this->isBlank()) {
            throw new EmptyImageException("You are trying to output an empty image.");
        }

        try {
            $format = $this->getFormat();
        } catch (\ImagickException $e) {
            $this->setFormat('jpg');
        }

        if ($format !== null) {
            $this->resource->setImageFormat($format);
        }

        return $this->resource->getImagesBlob();
    }

    /**
     * {@inheritdoc}
     */
    public function write($file, $jpeg_quality = null)
    {
        if ($jpeg_quality) {
            $this->resource->setImageCompression(\Imagick::COMPRESSION_JPEG);
            $this->resource->setImageCompressionQuality($jpeg_quality);
        }

        $this->resource->writeImages($file, true);
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
     * Manually sets the transparency pixel per pixel.
     *
     * This method properly sets the opacity on a png with transparency, by iterating pixel per pixel. It's a substitute
     * for the Imagick::setImageOpacity, since it doesn't handle well transparent backgrounds.
     *
     * @param \Imagick  $resource      The imagick resource to set opacity
     * @param int       $transparency  The transparency percentage, 0 to 100 - where 100 is fully transparent
     * @return \Imagick Returns        the Imagick object with changed opacity
     */
    public function setOpacity(\Imagick $resource, $transparency)
    {
        $alpha = $transparency / 100;

        if ($alpha >= 1) {
            return true;
        }

        $rows = $resource->getPixelIterator();

        foreach ($rows as $cols) {
            foreach ($cols as $pixel) {

                $current = $pixel->getColorValue(\Imagick::COLOR_ALPHA);

                $pixel->setColorValue(\Imagick::COLOR_ALPHA, (($current - $alpha > 0) ? ($current - $alpha) : (0)));

                $rows->syncIterator();
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function applyFilter(FilterInterface $filter, array $options = [])
    {
        $filter->apply($this->getResource(), $options);
    }
}
