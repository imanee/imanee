<?php

namespace Imanee\Image;

use Imanee\Image;

class Jpg extends Image {

    public function __construct($image_path = null, $width = 0, $height = 0)
    {
        if ($image_path !== null) {
            $this->resource = imagecreatefromjpeg($image_path);
        }

        parent::__construct($image_path);
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function factoryMethod()
	{

	}
}