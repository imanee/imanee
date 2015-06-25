<?php
/**
 * Default Imagick Provider for Imanee using the Imagick Extension
 */

namespace Imanee\ImageResource;

use Imanee\Exception\EmptyImageException;
use Imanee\Exception\ImageNotFoundException;
use Imanee\Filter\Imagick\BWFilter;
use Imanee\Filter\Imagick\ColorFilter;
use Imanee\Filter\Imagick\ModulateFilter;
use Imanee\Filter\Imagick\SepiaFilter;
use Imanee\Model\FilterInterface;
use Imanee\Model\ImageResourceInterface;
use Imanee\Imanee;
use Imanee\Drawer;
use Imanee\Model\ImageAnimatableInterface;
use Imanee\Model\ImageComposableInterface;
use Imanee\Model\ImageWritableInterface;
use Imanee\Model\ImageFilterableInterface;

class ImagickResource extends Resource implements
    ImageResourceInterface,
    ImageWritableInterface,
    ImageComposableInterface,
    ImageFilterableInterface,
    ImageAnimatableInterface
{
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

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew($width, $height, $background = 'white')
    {
        $this->width      = $width;
        $this->height     = $height;
        $this->background = $background;

        return $this->resource->newImage($width, $height, new \ImagickPixel($background));
    }

    /**
     * {@inheritdoc}
     */
    public function resize($width, $height, $bestfit = true)
    {
        if ($this->isBlank()) {
            throw new EmptyImageException("You are trying to resize an empty image.");
        }

        if ($this->resource->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, $bestfit)) {
            $this->updateResourceDimensions();

            return true;
        }

        return false;
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
     * {@inheritdoc}
     */
    public function rotate($degrees = 90.00, $background = 'transparent')
    {
        if ($this->resource->rotateimage(new \ImagickPixel($background), $degrees)) {
            $this->updateResourceDimensions();

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function crop($width, $height, $coordX, $coordY)
    {
        $this->width = $width;
        $this->height = $height;

        if ($this->resource->cropImage($width, $height, $coordX, $coordY)) {
            $this->updateResourceDimensions();

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function thumbnail($width, $height, $crop = false)
    {
        if ($crop) {
            $return = $this->resource->cropThumbnailImage($width, $height);
        } else {
            $return = $this->resource->thumbnailImage($width, $height, true);
        }

        if ($return) {
            $this->updateResourceDimensions();

            return true;
        }

        return false;
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

        return $this->resource->writeImages($file, true);
    }

    /**
     * Updates the computed width and height for the current Image Resource object
     */
    public function updateResourceDimensions()
    {
        $newsize = $this->resource->getImageGeometry();
        $this->width  = $newsize['width'];
        $this->height = $newsize['height'];
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

    // ImageComposableInterface

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

            $img = $image->getResource()->getResource();
        }

        if ($width and $height) {
            $img->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        }

        if ($transparency > 0) {
            $this->setOpacity($img, $transparency);
        }

        return $this->resource->compositeImage($img, \Imagick::COMPOSITE_OVER, $coordX, $coordY);
    }

    // ImageWritableInterface

    /**
     * {@inheritdoc}
     */
    public function annotate($text, $coordX, $coordY, $angle, Drawer $drawer)
    {
        return $this->resource->annotateImage($this->getImagickDraw($drawer), $coordX, $coordY, $angle, $text);
    }

    /**
     * {@inheritdoc}
     */
    public function getFontSize(Drawer $drawer)
    {
        return $drawer->getFontSize();
    }

    /**
     * {@inheritdoc}
     */
    public function getTextGeometry($text, Drawer $drawer)
    {
        $metrics = $this->resource->queryFontMetrics($this->getImagickDraw($drawer), $text);

        return [
            'width'  => $metrics['textWidth'],
            'height' => $metrics['textHeight'],
        ];
    }

    /**
     * Translates the Drawer object to a ImagickDraw
     * @param Drawer $drawer
     * @return \ImagickDraw
     */
    public function getImagickDraw(Drawer $drawer)
    {
        $imdraw = new \ImagickDraw();

        $imdraw->setFont($drawer->getFont());
        $imdraw->setFillColor($drawer->getFontColor());
        $imdraw->setFontSize($drawer->getFontSize());
        $imdraw->setTextAlignment($drawer->getTextAlign());

        return $imdraw;
    }

    // ImageAnimatableInterface

    /**
     * {@inheritdoc}
     */
    public function animate(array $frames, $delay = 20)
    {
        $gif = new \Imagick();
        $gif->setFormat('gif');

        foreach ($frames as $im) {
            if ($im instanceof Imanee) {
                $frame = $im->getResource()->getResource();
            } else {
                $frame = new \Imagick($im);
            }

            $frame->setImageDelay($delay);
            $gif->addImage($frame);
        }

        $imagickResource = new ImagickResource();
        $imagickResource->setResource($gif);

        $imanee = new Imanee();
        $imanee->setResource($imagickResource);
        $imanee->setFormat('gif');

        return $imanee;
    }

    // Other Helpers

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
     * @return bool     Returns true if successful
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
    public function loadFilters()
    {
        return [
            new BWFilter(),
            new ColorFilter(),
            new ModulateFilter(),
            new SepiaFilter(),
        ];
    }
}
