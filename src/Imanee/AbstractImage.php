<?php

namespace Imanee;

abstract class AbstractImage {
	
	protected $mime;
	protected $width;
	protected $height;

	static $accepted = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');

	/* FACTORY METHOD */
	public static function createResource($image_path)
	{
		if (!is_file($image_path))
			throw new exception("File not Found.");

		/** TODO: checks image format and returns the appropriate handler or exception **/
		$info = $this->getImageInfo($image_path);

		if (!in_array($info['mime'], self::$accepted))
			throw new exception("Image format not supported.");

		$this->mime   = $info['mime'];
		$this->width  = $info[0];
		$this->height = $info[1];
	}

	public static function getImageInfo($image_path)
	{
		return getimagesize($image_path);
	}

	protected abstract function factoryMethod();
}