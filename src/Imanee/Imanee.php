<?php

namespace Imanee;

use Imanee\Layer\ImageLayer;
use Imanee\Layer\LayerController;

class Imanee extends ConfigContainer {

	protected $resource;

    public function __construct($path = null)
	{
        if ($path !== null)
            $this->load($path);

        parent::__construct([
            'drawer' => new Drawer(),
            'layer'  => new LayerController()
        ]);

        return $this;
	}

    public function load($image_path)
    {
        $this->resource = new Image($image_path);

        return $this;
    }

    public function getMime()
    {
        return $this->resource->mime;
    }

    public function resize($width, $height)
    {
        $this->resource->resize($width, $height);
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

    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    public function drawText(Drawer $drawer, $text)
    {
        /** save for later rendering, as a layer with text */
        return $this;
    }

    public function draw(Drawer $drawer)
    {
        $drawer->draw();

        return $this;
    }

    public function setDrawer(Drawer $drawer)
    {
        $this->drawer = $drawer;

        return $this;
    }

    public function output()
    {
        return $this->resource->output();
    }
}