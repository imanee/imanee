<?php

namespace Imanee;

class Imanee extends \Pimple {

	public function __construct(array $values = null)
	{
		if ($values['image_path'] !== null) {
			/* GET IMAGE INFO */
			$this->image_info = getimagesize($image_path);

			/* CREATE THE HANDLER - IMPLEMENT FACTORY PATTERN */
			$this->image_path = $image_path;
			$this->image_resource = Image::createResource($image_path);	
		}

        $app = $this;

        $this['drawer'] = $this->share(function() use($app) {
            return new Drawer();
        });

        if ($values !== null) {
            foreach ($values as $key => $value) {
                $this[$key] = $value;
            }
        }

        return $this;
	}

    public function loadImage($image_path)
    {

    }

    public function setSize()
    {
        return $this;
    }

    public function setBackground()
    {
        return $this;
    }

    public function writeText()
    {
        return $this;
    }

    public function setDrawer(Drawer $drawer)
    {
        $this['drawer'] = $drawer;

        return $this;
    }
}