<?php

namespace Imanee;

use Imanee\Exception\EmptyImageException;
use Imanee\Exception\ImageNotFoundException;

class Image {

    private $resource;
    public  $image_path;
    public  $mime;
	public  $width;
	public  $height;
    public  $background;

    public function __construct($image_path = null)
    {
        $this->resource = new \Imagick();

        if ($image_path !== null) {
            $this->image_path = $image_path;
            $this->load();
        }

    }

    public function createNew($width, $height, $background = 'white')
    {
        $this->width      = $width;
        $this->height     = $height;
        $this->background = $background;

        $this->resource->newImage($width, $height, new \ImagickPixel($background));
    }

	public function load()
	{
        if (!is_file($this->image_path))
            throw new ImageNotFoundException("File not Found.");

        $this->loadImageInfo();
        $this->resource = new \Imagick($this->image_path);

        return $this;
	}

    public function getResource()
    {
        return $this->resource;
    }

    public function resize($width, $height)
    {
        if ($this->isBlank())
            throw new EmptyImageException("You are trying to resize an empty image.");

        $this->resource->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        $newsize = $this->resource->getImageGeometry();

        $this->width  = $newsize['width'];
        $this->height = $newsize['height'];
    }

	public function loadImageInfo()
	{
        if (!is_file($this->image_path))
            throw new ImageNotFoundException("File not Found.");

        $info = getimagesize($this->image_path);

        $this->mime   = $info['mime'];
        $this->width  = $info[0];
        $this->height = $info[1];

	}

    public function setFormat($format)
    {
        $this->resource->setimageformat($format);
    }

    public function getFormat()
    {
        return $this->resource->getimageformat();
    }

    /**
     * Returns a previously defined (e.g. when creating a new image) background color
     * @return string The string previously used to define the background
     */
    public function getBackground()
    {
        return $this->background;
    }

    public function annotate($text, $coordX, $coordY, $angle, Drawer $drawer)
    {
        $this->resource->annotateimage($drawer->getDrawer(),$coordX, $coordY, $angle, $text );
    }

    public function getTextGeometry($text, Drawer $drawer)
    {
        $metrics = $this->resource->queryFontMetrics($drawer->getDrawer(), $text);

        return array(
            'width'  => $metrics['textWidth'],
            'height' => $metrics['textHeight'],
        );
    }

    public function placeText($text, $place_constant, Drawer $drawer)
    {
        $textsize = $this->getTextGeometry($text, $drawer);
        list($coordX, $coordY) = $this->getPlacementCoordinates($textsize, $place_constant);

        $this->resource->annotateimage($drawer->getDrawer(),$coordX, $coordY, 0, $text);
    }

    public function placeImage($image_path, $place_constant, $width = 0, $height = 0)
    {
        $img = new \Imagick($image_path);

        if ($width AND $height) {
            $img->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        }

        list($coordX, $coordY) = $this->getPlacementCoordinates($img->getimagegeometry(), $place_constant);
        $this->resource->compositeimage($img, \Imagick::COMPOSITE_OVER, $coordX, $coordY);
    }

    public function output($format = null)
    {
        if ($this->isBlank())
            throw new EmptyImageException("You are trying to output an empty image.");

        if ($format !== null) {
            $this->resource->setimageformat($format);
        }

        return $this->resource->getImageBlob();
    }

    public function isBlank()
    {
        return !$this->width;
    }

    public function getPlacementCoordinates($resource_size, $place_constant)
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
}