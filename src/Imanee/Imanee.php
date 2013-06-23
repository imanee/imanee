<?php

namespace Imanee;

use Imanee\Layer\LayerController;

class Imanee extends ConfigContainer {

	public function __construct(array $values = [])
	{
        parent::__construct($values, [
            'drawer' => new Drawer(),
            'layers' => new LayerController()
        ]);

        return $this;
	}

    public static function load($image_path)
    {
        return Image::loadFromFile($image_path);
    }

    public static function createNew($width, $height)
    {
        return Image::createNew($width, $height);
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

}