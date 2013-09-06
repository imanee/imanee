<?php
/**
 * Abstract Layer
 */

namespace Imanee\Layer;


abstract class AbstractLayer {

    /** @var \Imanee\Image The image resource */
    protected $resource;

    /** @var int the X coordinate to place this layer */
    public $x = 0;

    /** @var int the Y coordinate to place this layer */
    public $y = 0;

    /** @var int the width for this layer */
    public $width = 0;

    /** @var int the height for this layer */
    public $height = 0;

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param \Imanee\Image $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return \Imanee\Image
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }


}