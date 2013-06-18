<?php

namespace Imanee;

class Imanee {
	
	private $image_path;
	private $image_resource;
	private $image_info;
	private $function_mapper;

	function __construct($image_path = null)
	{
		if ($image_path !== null) {
			/* GET IMAGE INFO */
			$this->image_info = getimagesize($image_path);

			/* CREATE THE HANDLER - IMPLEMENT FACTORY PATTERN */
			$this->image_path = $image_path;
			$this->image_resource = Image::createResource($image_path);	
		}
		
	}
}