<?php

namespace Imanee\ImageResource;

abstract class Resource
{
    /**
     * Underlying resource.
     *
     * @var mixed
     */
    protected $resource;

    /**
     * Path to the current image resource.
     *
     * @var string
     */
    public $imagePath;

    /**
     * Image mime type.
     *
     * @var string
     */
    public $mime;

    /**
     * Format (based on mime type).
     *
     * @var string
     */
    public $format;

    /**
     * Image width.
     *
     * @var int
     */
    public $width;

    /**
     * Image height.
     *
     * @var int
     */
    public $height;

    /**
     * Image background.
     *
     * @var string
     */
    public $background;

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     *
     * @return $this
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        $this->updateResourceDimensions();

        return $this;
    }

    /**
     * @return $this
     */
    abstract protected function updateResourceDimensions();

    /**
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }
}
