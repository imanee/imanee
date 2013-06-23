<?php

namespace Imanee;

use Imanee\Exception\ImageNotFoundException;
use Imanee\Image\Blank;

abstract class Image {

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
            $this->loadImageInfo();
        } else {
            $this->width  = $width;
            $this->height = $height;
        }
    }

	public static function loadFromFile($image_path)
	{
		self::$supported = [
            'image/jpg'  => 'Imanee\\Image\\Jpg',
            'image/jpeg' => 'Imanee\\Image\\Jpg',
        ];

        if (!is_file($image_path))
            throw new ImageNotFoundException("File not Found.");

        $info = getimagesize($image_path);
        $mime = $info['mime'];

        if (!isset(self::$supported[$mime]))
            throw new exception("Image format not supported.");

        return new self::$supported[$mime]($image_path);

	}

    public static function createNew($width, $height)
    {
        return new Blank(null, $width, $height);
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

}