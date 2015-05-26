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
use Imanee\Model\ImageResourceInterface;

class GDResource implements ImageResourceInterface
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

        return $this;
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
     * Gets the best fit for a given width / height where the provided values will be used as **maximum** values
     * (the resulting image won't ever pass these dimensions)
     * @param $width
     * @param $height
     * @return array
     */
    public function getBestFit($width, $height)
    {
        $finalWidth = $width;
        $finalHeight = ($finalWidth * $this->height) / $this->width;

        if ($finalHeight > $height) {
            $finalHeight = $height;
            $finalWidth = ($finalHeight * $this->width) / $this->height;
        }

        return ['width' => $finalWidth, 'height' =>$finalHeight];
    }

    /**
     * Gets the best fit for a given width / height where the provided values will be used as **minimum** values
     * (the resulting image can be bigger, there won't be any blank spaces)
     * @param $width
     * @param $height
     * @return array
     */
    public function getMaxFit($width, $height)
    {
        $finalWidth = $width;
        $finalHeight = ($finalWidth * $this->height) / $this->width;

        if ($finalHeight < $height) {
            $finalHeight = $height;
            $finalWidth = ($finalHeight * $this->width) / $this->height;
        }

        return ['width' => $finalWidth, 'height' =>$finalHeight];
    }

    /**
     * {@inheritdoc}
     */
    public function resize($width, $height, $bestfit = true)
    {
        $finalWidth = $width;
        $finalHeight = $height;

        if ($bestfit) {
            $bestFitDimensions = $this->getBestFit($width, $height);
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

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function rotate($degrees = 90.00, $background = 'transparent')
    {
        $this->resource = imagerotate($this->resource, $degrees, $this->loadColor($background));

        return $this;
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

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function thumbnail($width, $height, $crop = false)
    {
        $sourceX = 0;
        $sourceY = 0;

        if ($crop) {
            $resizeDimensions = $this->getMaxFit($width, $height);
            $finalWidth = $width;
            $finalHeight = $height;
            $sourceX = ($this->getWidth() / 2) - ($width / 2);
            $sourceY = ($this->getHeight() / 2) - ($height / 2);
        } else {
            $resizeDimensions = $this->getBestFit($width, $height);
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

        return $this;
    }

    /**
     * Outputs the image data as a string.
     *
     * @param string $format (optional) overwrites the current image format.
     * use it if you did not explicitly set the format on new images before calling output.
     * if no format was previously defined, it will use jpg
     *
     * @return string The image data as a string
     * @throws EmptyImageException
     * @throws UnsupportedFormatException
     */
    public function output($format = null)
    {
        switch ($this->format) {
            case "jpg":
            case "jpeg":
                imagejpeg($this->resource);
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
     * Saves the image to disk. If the second param is provided, will try to compress the image using JPEG compression.
     *
     * The format will be decided based on the extension used for the filename. If, for instance,
     * a "img.png" is provided, the image will be saved as PNG and the compression will not take affect.
     *
     * @param string $file The file path to save the image
     * @param int $jpeg_quality (optional) the quality for JPEG files, 1 to 100 where 100 means no compression
     * (higher quality and bigger file)
     */
    public function write($file, $jpeg_quality = null)
    {
        // TODO: Implement write() method.
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
}
