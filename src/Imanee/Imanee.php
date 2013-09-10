<?php

namespace Imanee;

class Imanee {

    /** @var \Imanee\Image Resource */
    protected $resource;

    /** @var \Imanee\Drawer The drawer settings */
    protected $drawer;

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

    /**
     * @param string $path a path to a image file - convenient way open an image without using the load() method
     */
    public function __construct($path = null)
	{
        $this->drawer = new Drawer();

        $this->load($path);

        return $this;
	}

    /**
     * Loads an image from a file
     * @param string $image_path  The path to the image
     *
     * @return $this
     */
    public function load($image_path)
    {
        $this->resource = new Image($image_path);

        return $this;
    }

    /**
     * Creates a new "blank" image
     *
     * @param int    $width  The width of the image
     * @param int    $height The height of the image
     * @param string $background The image background, Defaults to white
     *
     * @return $this
     */
    public function newImage($width, $height, $background = 'white')
    {
        $this->resource->createNew($width, $height, $background);

        return $this;
    }

    /**
     * Gets the mime type associated with the current loaded resource
     *
     * @return string the mime type
     */
    public function getMime()
    {
        return $this->resource->mime;
    }

    /**
     * Sets the format to the current loaded resource.
     *
     * @param string $format The image format, e.g: "jpeg"
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->resource->setFormat($format);

        return $this;
    }

    /**
     * Gets the current format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->resource->getFormat();
    }

    /**
     * Resizes the current image resource
     *
     * @param int $width  The new width
     * @param int $height The new height
     *
     * @return $this
     */
    public function resize($width, $height)
    {
        $this->resource->resize($width, $height);

        return $this;
    }

    /**
     * Places a text on top of the current image - convenient way to write text using relative positioning.
     * To overwrite the current Drawer settings, create a custom Drawer object and use the method ->setDrawer before
     *
     * @param string $text           Text to be written
     * @param int    $size           Font size
     * @param int    $place_constant One of the Imanee:IM_POS constants - defaults to IM_POS_TOP_LEFT
     *
     * @return $this
     */
    public function placeText($text, $size = 12, $place_constant = Imanee::IM_POS_TOP_LEFT)
    {
        $this->drawer->setFontSize($size);

        $this->resource->placeText($text, $place_constant, $this->drawer);

        return $this;
    }

    /**
     * Writes text to an image
     *
     * @param string         $text   The text to be written
     * @param int            $coordX The X coordinate for text placement
     * @param int            $coordY The Y coordinate for text placement
     * @param int            $size   The font size
     * @param int            $angle  The angle (defaults to 0, plain)
     * @param \Imanee\Drawer $custom_drawer A custom drawer object to overwrite the default text settings
     *
     * @return $this
     */
    public function annotate($text, $coordX, $coordY, $size = 12, $angle = 0, $custom_drawer = null)
    {
        $drawer = $custom_drawer ?: $this->drawer;

        if ($size)
            $drawer->setFontSize($size);

        $this->resource->annotate($text, $coordX, $coordY, $angle, $drawer);

        return $this;
    }

    /**
     * Sets the drawer. Use this to change the default text settings
     *
     * @param Drawer $drawer
     * @return $this
     */
    public function setDrawer(Drawer $drawer)
    {
        $this->drawer = $drawer;

        return $this;
    }

    /**
     * Gets the current drawer in use
     *
     * @return Drawer
     */
    public function getDrawer()
    {
        return $this->drawer;
    }

    /**
     * Places an image on top of the current resource. If the width and height are supplied,
     * will perform a resize before placing the image.
     *
     * @param string $image_path     The path to the image to be placed
     * @param int    $place_constant One of the Imanee::IM_POS constants, defaults to IM_POS_TOP_LEFT (top left corner)
     * @param int    $width          (optional) specifies a width for the placement
     * @param int    $height         (optional) specifies a height for the placement
     * @return $this
     */
    public function placeImage($image_path, $place_constant = Imanee::IM_POS_TOP_LEFT, $width = null, $height = null)
    {
        $this->resource->placeImage($image_path, $place_constant, $width, $height);

        return $this;
    }

    /**
     * Gets the width of the current image resource
     *
     * @return int the width
     */
    public function getWidth()
    {
        return $this->resource->width;
    }

    /**
     * Gets the height of the current image resource
     *
     * @return int the height
     */
    public function getHeight()
    {
        return $this->resource->height;
    }

    /**
     * Output the current image resource as a string
     *
     * @param string $format The image format (overwrites the currently defined format)
     *
     * @return string The image data as a string
     */
    public function output($format = null)
    {
        return $this->resource->output($format);
    }


}