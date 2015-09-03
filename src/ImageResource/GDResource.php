<?php

namespace Imanee\ImageResource;

use Imanee\Drawer;
use Imanee\Exception\ImageNotFoundException;
use Imanee\Exception\UnsupportedFormatException;
use Imanee\Filter\GD\BWFilter;
use Imanee\Filter\GD\ColorFilter;
use Imanee\Filter\GD\ModulateFilter;
use Imanee\Filter\GD\SepiaFilter;
use Imanee\Filter\GD\GaussianFilter;
use Imanee\Imanee;
use Imanee\Model\ImageComposableInterface;
use Imanee\Model\ImageResourceInterface;
use Imanee\Model\ImageWritableInterface;
use Imanee\PixelMath;
use Imanee\Model\ImageFilterableInterface;

/**
 * GD-based image manipulator.
 */
class GDResource extends Resource implements
    ImageResourceInterface,
    ImageComposableInterface,
    ImageWritableInterface,
    ImageFilterableInterface
{
    /**
     * Underlying image resource handle.
     *
     * @var resource
     */
    public $resource;

    /**
     * Path to the current image resource.
     *
     * @var string
     */
    public $imagePath;

    /**
     * Image mime type.
     *
     * @var string
     */
    public $mime;

    /**
     * Format (based on mime type).
     *
     * @var string
     */
    public $format;

    /**
     * Image width.
     *
     * @var int
     */
    public $width;

    /**
     * Image height.
     *
     * @var int
     */
    public $height;

    /**
     * Image background.
     *
     * @var string
     */
    public $background;

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

        switch ($this->getMime()) {

            case "image/jpeg":
            case "image/jpg":
            case "image/pjpeg":
            case "image/pjpg":
                $this->format = "jpg";
                $this->resource = imagecreatefromjpeg($imagePath);
                break;

            case "image/gif":
                $this->format = "gif";
                $this->resource = imagecreatefromgif($imagePath);
                break;

            case "image/png":
                $this->format = "png";
                $this->resource = imagecreatefrompng($imagePath);
                break;

            default:
                throw new UnsupportedFormatException(
                    sprintf("The format '%s' is not supported by this Resource.", $this->getMime())
                );
                break;
        }

        return $this;
    }

    /**
     * @param string $color
     *
     * @return int
     */
    public function loadColor($color)
    {
        return GDPixel::load($color, $this->getResource());
    }

    /**
     * {@inheritdoc}
     */
    public function createNew($width, $height, $background = 'white')
    {
        if ($this->resource = imagecreatetruecolor($width, $height)) {
            imagefill($this->getResource(), 0, 0, $this->loadColor($background));
            $this->updateResourceDimensions();

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function resize($width, $height, $bestfit = true)
    {
        $finalWidth = $width;
        $finalHeight = $height;

        if ($bestfit) {
            $bestFitDimensions = PixelMath::getBestFit($width, $height, $this->getWidth(), $this->getHeight());
            $finalWidth = $bestFitDimensions['width'];
            $finalHeight = $bestFitDimensions['height'];
        }

        $resized = imagecreatetruecolor($finalWidth, $finalHeight);

        if (imagecopyresampled(
            $resized,
            $this->getResource(),
            0,
            0,
            0,
            0,
            $finalWidth,
            $finalHeight,
            $this->getWidth(),
            $this->getHeight()
        )) {
            $this->resource = $resized;
            $this->updateResourceDimensions();

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rotate($degrees = 90.00, $background = 'transparent')
    {
        if ($this->resource = imagerotate($this->getResource(), $degrees, $this->loadColor($background))) {
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
        $cropped = imagecreatetruecolor($width, $height);

        if (imagecopyresampled(
            $cropped,
            $this->getResource(),
            0,
            0,
            $coordX,
            $coordY,
            $width,
            $height,
            $width,
            $height
        )) {
            $this->resource = $cropped;
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
        $sourceX = 0;
        $sourceY = 0;

        if ($crop) {
            $resizeDimensions = PixelMath::getMaxFit($width, $height, $this->getWidth(), $this->getHeight());
            $finalWidth = $width;
            $finalHeight = $height;
            $sourceX = ($resizeDimensions['width'] / 2) - ($width / 2);
            $sourceY = ($resizeDimensions['height'] / 2) - ($height / 2);
        } else {
            $resizeDimensions = PixelMath::getBestFit($width, $height, $this->getWidth(), $this->getHeight());
            $finalWidth = $resizeDimensions['width'];
            $finalHeight = $resizeDimensions['height'];
        }

        $this->resize($resizeDimensions['width'], $resizeDimensions['height'], false);
        $thumb = imagecreatetruecolor($finalWidth, $finalHeight);

        if (imagecopyresampled(
            $thumb,
            $this->getResource(),
            0,
            0,
            $sourceX,
            $sourceY,
            $finalWidth,
            $finalHeight,
            $finalWidth,
            $finalHeight
        )) {
            $this->resource = $thumb;
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
        $format = $format ?: $this->format;
        ob_start();
        switch ($format) {
            case "jpg":
            case "jpeg":
                imagejpeg($this->getResource(), null, 90);
                break;

            case "gif":
                imagegif($this->getResource());
                break;

            case "png":
                imagesavealpha($this->getResource(), true);
                imagepng($this->getResource());
                break;

            default:
                ob_end_clean();
                throw new UnsupportedFormatException(
                    sprintf("The format '%s' is not supported by this Resource.", $this->getMime())
                );
        }

        $image = ob_get_contents();
        ob_end_clean();

        return $image;
    }

    /**
     * {@inheritdoc}
     */
    public function write($file, $jpeg_quality = null)
    {
        $jpeg_quality = $jpeg_quality ?: 80;
        $this->setFormat($this->getExtensionByFileName($file));

        switch ($this->format) {
            case "jpg":
            case "jpeg":
                return imagejpeg($this->getResource(), $file, $jpeg_quality);
                break;

            case "gif":
                return imagegif($this->getResource(), $file);
                break;

            case "png":
                imagesavealpha($this->getResource(), true);
                return imagepng($this->getResource(), $file);
                break;

            default:
                throw new UnsupportedFormatException(
                    sprintf("The format '%s' is not supported by this Resource.", $this->getMime())
                );
        }
    }

    /**
     * A workaround to keep compatibility with Imagick when writing images to disk.
     *
     * @param string $filepath
     *
     * @return string
     */
    public function getExtensionByFileName($filepath)
    {
        $path = pathinfo($filepath);

        return $path['extension'];
    }

    /**
     * {@inheritdoc}
     */
    public function updateResourceDimensions()
    {
        $this->width = imagesx($this->getResource());
        $this->height = imagesy($this->getResource());
    }

    /**
     * {@inheritdoc}
     */
    public function compositeImage($image, $coordX, $coordY, $width = 0, $height = 0, $transparency = 0)
    {
        if (!is_object($image)) {
            $image = new Imanee($image, new GDResource());
        }

        if (! ($image instanceof \Imanee\Imanee)) {
            throw new \Exception('Object not supported. It must be an instance of Imanee');
        }

        if ($width and $height) {
            $dimensions = PixelMath::getBestFit($width, $height, $image->getWidth(), $image->getHeight());
            $width = $dimensions['width'];
            $height = $dimensions['height'];
        } else {
            $width = $image->getWidth();
            $height = $image->getHeight();
        }

        /* TODO: implement pixel per pixel transparency */

        return imagecopyresampled(
            $this->getResource(),
            $image->getResource()->getResource(),
            $coordX,
            $coordY,
            0,
            0,
            $width,
            $height,
            $image->getWidth(),
            $image->getHeight()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFontSize(Drawer $drawer)
    {
        return $drawer->getFontSize() * 0.75;
    }

    /**
     * {@inheritdoc}
     */
    public function annotate($text, $coordX, $coordY, $angle, Drawer $drawer)
    {
        $color = GDPixel::load($drawer->getFontColor(), $this->getResource());

        return imagettftext(
            $this->getResource(),
            $this->getFontSize($drawer),
            $angle,
            $coordX,
            $coordY,
            $color,
            $drawer->getFont(),
            $text
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTextGeometry($text, Drawer $drawer)
    {
        $coords = imagettfbbox($this->getFontSize($drawer), 0, $drawer->getFont(), $text);

        $width = $coords[2] - $coords[0];
        $height = $coords[1] - $coords[7];

        return ['width' => $width, 'height' => $height];
    }

    /**
     * {@inheritdoc}
     */
    public function loadFilters()
    {
        return [
            new BWFilter(),
            new SepiaFilter(),
            new ColorFilter(),
            new ModulateFilter(),
            new GaussianFilter()
        ];
    }
}
