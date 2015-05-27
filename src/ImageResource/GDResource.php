<?php
/**
 * GDResource for simple image operations using GD - fallback for when Imagick extension is not available
 */

namespace Imanee\ImageResource;


use Imanee\Drawer;
use Imanee\Exception\EmptyImageException;
use Imanee\Exception\ImageNotFoundException;
use Imanee\Exception\UnsupportedFormatException;
use Imanee\Imanee;
use Imanee\Model\FilterInterface;
use Imanee\Model\ImageComposableInterface;
use Imanee\Model\ImageResourceInterface;
use Imanee\PixelMath;

class GDResource implements ImageResourceInterface, ImageComposableInterface
{
    /** @var resource the image resource */
    public $resource;

    /** @var string the path to the current image resource if loaded from file */
    public $imagePath;

    /** @var string the image mime type */
    public $mime;

    /** @var string helper variable with image format from mimetype */
    public $format;

    /** @var  int the image width */
    public $width;

    /** @var int the image height */
    public $height;

    /** @var string the image background if defined */
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
    }

    public function loadColor($color)
    {
        return GDPixel::load($color, $this->getResource());
    }

    /**
     * {@inheritdoc}
     */
    public function createNew($width, $height, $background = 'white')
    {
        $this->resource = imagecreatetruecolor($width, $height);
        imagefill($this->resource, 0, 0, $this->loadColor($background));
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        return $this->resource;
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
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormat($format)
    {
        $this->format = $format;
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
        $finalWidth = $width;
        $finalHeight = $height;

        if ($bestfit) {
            $bestFitDimensions = PixelMath::getBestFit($width, $height, $this->getWidth(), $this->getHeight());
            $finalWidth = $bestFitDimensions['width'];
            $finalHeight = $bestFitDimensions['height'];
        }

        $resized = imagecreatetruecolor($finalWidth, $finalHeight);

        imagecopyresampled(
            $resized,
            $this->resource,
            0,
            0,
            0,
            0,
            $finalWidth,
            $finalHeight,
            $this->getWidth(),
            $this->getHeight()
        );

        $this->resource = $resized;
        $this->width = $finalWidth;
        $this->height = $finalHeight;
    }

    /**😻
     * {@inheritdoc}
     */
    public function rotate($degrees = 90.00, $background = 'transparent')
    {
        $this->resource = imagerotate($this->resource, $degrees, $this->loadColor($background));
    }

    /**
     * {@inheritdoc}
     */
    public function crop($width, $height, $coordX, $coordY)
    {
        $cropped = imagecreatetruecolor($width, $height);

        imagecopyresampled(
            $cropped,
            $this->resource,
            0,
            0,
            $coordX,
            $coordY,
            $width,
            $height,
            $width,
            $height
        );

        $this->resource = $cropped;
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

        imagecopyresampled(
            $thumb,
            $this->resource,
            0,
            0,
            $sourceX,
            $sourceY,
            $finalWidth,
            $finalHeight,
            $finalWidth,
            $finalHeight
        );

        $this->resource = $thumb;
    }

    /**
     * {@inheritdoc}
     */
    public function output($format = null)
    {
        $format = $format ?: $this->format;
        switch ($format) {
            case "jpg":
            case "jpeg":
                imagejpeg($this->resource, null, 90);
                break;

            case "gif":
                imagegif($this->resource);
                break;

            case "png":
                imagepng($this->resource);
                break;

            default:
                throw new UnsupportedFormatException(
                    sprintf("The format '%s' is not supported by this Resource.", $this->getMime())
                );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write($file, $jpeg_quality = null)
    {
        $jpeg_quality = $jpeg_quality ?: 80;

        switch ($this->format) {
            case "jpg":
            case "jpeg":
                imagejpeg($this->resource, $file, $jpeg_quality);
                break;

            case "gif":
                imagegif($this->resource, $file);
                break;

            case "png":
                imagepng($this->resource, $file);
                break;

            default:
                throw new UnsupportedFormatException(
                    sprintf("The format '%s' is not supported by this Resource.", $this->getMime())
                );
        }
    }

    /**
     * Applies a filter compatible with the current Resource Provider
     * @param FilterInterface $filter
     * @param array $options
     * @return mixed
     */
    public function applyFilter(FilterInterface $filter, array $options = [])
    {
        // TODO: Implement applyFilter() method.
    }

    /** ImageComposableInterface */

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

        imagecopyresampled(
            $this->resource,
            $image->getIMResource(),
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
}
