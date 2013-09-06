<?php

namespace Imanee;

use Imanee\Exception\ImageNotFoundException;

class Image {

    protected $resource;
    public $image_path;
    public $mime;
	public $width;
	public $height;

	static $supported;

    public function __construct($image_path = null, $width = 0, $height = 0)
    {
        if ($image_path !== null) {
            $this->image_path = $image_path;
            $this->load();
        } else {
            $this->width  = $width;
            $this->height = $height;
        }
    }

	public function load()
	{
        if (!is_file($this->image_path))
            throw new ImageNotFoundException("File not Found.");

        $this->loadImageInfo();
        $this->resource = new \Imagick($this->image_path);

        return $this;
	}

    public function resize($width, $height)
    {
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

    public function output($format = null)
    {
        return $this->resource->getImageBlob();
    }
}