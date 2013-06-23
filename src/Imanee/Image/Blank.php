<?php
/**
* An empty image
 */

namespace Imanee\Image;


use Imanee\Image;

class Blank extends Image{

    protected $resource;

    public function __construct($image_path = null, $width = 0, $height = 0)
    {
        $this->resource = imagecreatetruecolor($width, $height);

        parent::__construct($image_path, $width, $height);
    }
}