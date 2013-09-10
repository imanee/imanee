<?php

namespace Imanee;

use Imanee\Layer\ImageLayer;
use Imanee\Layer\LayerController;

class Imanee extends ConfigContainer {

    protected $resource;
    protected $drawer;
    protected $layer;

    const IM_POS_CENTER        = 1;
    const IM_POS_LEFT          = 2;
    const IM_POS_RIGHT         = 3;

    const IM_POS_TOP_LEFT      = 10;
    const IM_POS_TOP_RIGHT     = 11;
    const IM_POS_TOP_CENTER    = 12;
    const IM_POS_MID_LEFT      = 13;
    const IM_POS_MID_RIGHT     = 14;
    const IM_POS_MID_CENTER    = 15;
    const IM_POS_BOTTOM_LEFT   = 16;
    const IM_POS_BOTTOM_RIGHT  = 17;
    const IM_POS_BOTTOM_CENTER = 18;


    public function __construct($path = null)
	{
        $this->drawer = new Drawer();

        $this->load($path);

        return $this;
	}

    public function load($image_path)
    {
        $this->resource = new Image($image_path);

        return $this;
    }

    public function newImage($width, $height, $background = 'white')
    {
        $this->resource->createNew($width, $height, $background);

        return $this;
    }

    public function addLayer($path = null)
    {

    }

    public function getMime()
    {
        return $this->resource->mime;
    }

    public function setFormat($format)
    {
        $this->resource->setFormat($format);

        return $this;
    }

    public function getFormat()
    {
        return $this->resource->getFormat();
    }

    public function resize($width, $height)
    {
        $this->resource->resize($width, $height);

        return $this;
    }

    public function add($image_path)
    {
        $this->layer->add(new ImageLayer($image_path));

        return $this;
    }

    public function setSize($width, $height)
    {
        $this->width  = $width;
        $this->height = $height;

        return $this;
    }

    public function placeText($text, $size = 12, $place_constant = Imanee::IM_POS_TOP_LEFT)
    {
        $this->drawer->setFontSize($size);

        $this->resource->placeText($text, $place_constant, $this->drawer);

        return $this;
    }

    public function annotate($text, $coordX, $coordY, $size = 12, $angle = 0, $custom_drawer = null)
    {
        $drawer = $custom_drawer ?: $this->drawer;

        if ($size)
            $drawer->setFontSize($size);

        $this->resource->annotate($text, $coordX, $coordY, $angle, $drawer);

        return $this;
    }

    public function setDrawer(Drawer $drawer)
    {
        $this->drawer = $drawer;

        return $this;
    }

    public function getDrawer()
    {
        return $this->drawer;
    }

    public function placeImage($image_path, $place_constant = Imanee::IM_POS_TOP_LEFT, $width = null, $height = null)
    {
        $this->resource->placeImage($image_path, $place_constant, $width, $height);

        return $this;
    }

    public function getWidth()
    {
        return $this->resource->width;
    }

    public function getHeight()
    {
        return $this->resource->height;
    }

    public function output()
    {
        return $this->resource->output();
    }


}