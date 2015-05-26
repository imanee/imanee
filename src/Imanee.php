<?php

namespace Imanee;

use Imanee\Exception\FilterNotFoundException;
use Imanee\Exception\ImageNotFoundException;
use Imanee\Filter\BWFilter;
use Imanee\Filter\ColorFilter;
use Imanee\Filter\ModulateFilter;
use Imanee\Filter\SepiaFilter;
use Imanee\ImageResource\ImagickResource;
use Imanee\Model\ImageResourceInterface;

class Imanee
{
    /** @var \Imanee\Model\ImageResourceInterface Resource */
    protected $resource;

    /** @var \Imanee\Drawer The drawer settings */
    protected $drawer;

    /** @var  Imanee[] Frames */
    protected $frames;

    /** @var  \Imanee\FilterResolver The filter Resolver */
    protected $filterResolver;

    const IM_POS_CENTER = 1;
    const IM_POS_LEFT = 2;
    const IM_POS_RIGHT = 3;

    const IM_POS_TOP_LEFT = 10;
    const IM_POS_TOP_RIGHT = 11;
    const IM_POS_TOP_CENTER = 12;
    const IM_POS_MID_LEFT = 13;
    const IM_POS_MID_RIGHT = 14;
    const IM_POS_MID_CENTER = 15;
    const IM_POS_BOTTOM_LEFT = 16;
    const IM_POS_BOTTOM_RIGHT = 17;
    const IM_POS_BOTTOM_CENTER = 18;

    /**
     * @param string $path a path to a image file - convenient way to open an image without using the load() method
     * @param ImageResourceInterface $resource A valid object implementing the ImageResourceInterface; defaults to null,
     * in which case a resource will be automatically created based on current extensions available
     */
    public function __construct($path = null, ImageResourceInterface $resource = null)
    {
        $this->drawer = new Drawer();
        $this->filterResolver = new FilterResolver($this->getFilters());

        if (!$resource) {
            $provider = new ResourceProvider();
            $resource = $provider->createImageResource();
        }

        $this->resource = $resource;

        if ($path) {
            $this->load($path);
        }

        return $this;
    }



    /**
     * {@inheritdoc}
     */
    public function __clone()
    {
        $this->resource = clone $this->resource;
    }

    /**
     * Loads an image from a file
     * @param string $imagePath The path to the image
     *
     * @return $this
     */
    public function load($imagePath)
    {
        $this->resource->load($imagePath);

        return $this;
    }

    /**
     * Creates a new "blank" image
     *
     * @param int $width The width of the image
     * @param int $height The height of the image
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
     * Gets the mime type associated with the current resource (if available)
     *
     * @return string the mime type
     */
    public function getMime()
    {
        return $this->resource->getMime();
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
     * @param int $width The new width
     * @param int $height The new height
     * @param bool $bestfit When set to true (default), will fit the image inside the provided box dimensions.
     * When set to false, will force resize to the specified dimensions, which may cause the resulting image to be
     * out of proportion.
     *
     * @return $this
     */
    public function resize($width, $height, $bestfit = true)
    {
        $this->resource->resize($width, $height, $bestfit);

        return $this;
    }

    /**
     * Places a text on top of the current image - convenient way to write text using relative positioning.
     * To overwrite the current Drawer settings, create a custom Drawer object and use the method ->setDrawer before
     *
     * @param string $text Text to be written
     * @param int $place_constant One of the Imanee:IM_POS constants - defaults to IM_POS_TOP_LEFT
     * @param int $fitWidth If a positive value is provided, will change the font size to fit
     * the text in this width
     * @param int $fontSize The font size. Defaults to the current font size defined in the Drawer
     *
     * @return $this
     */
    public function placeText($text, $place_constant = Imanee::IM_POS_TOP_LEFT, $fitWidth = 0, $fontSize = 0)
    {
        if ($fontSize) {
            $this->drawer->setFontSize($fontSize);
        }
        $this->resource->placeText($text, $place_constant, $this->drawer, $fitWidth);

        return $this;
    }

    /**
     * Writes text to an image
     *
     * @param string $text The text to be written
     * @param int $coordX The X coordinate for text placement
     * @param int $coordY The Y coordinate for text placement
     * @param int $size The font size
     * @param int $angle The angle (defaults to 0, plain)
     *
     * @return $this
     */
    public function annotate($text, $coordX, $coordY, $size = null, $angle = 0)
    {
        $drawer = $this->getDrawer();

        if ($size) {
            $drawer->setFontSize($size);
        }
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
     * @param mixed $image Path to an image on filesystem or an Imanee Object
     * @param int $coordX Coord X for placement
     * @param int $coordY Coord Y for placement
     * @param int $width (optional) specifies a width for the placement
     * @param int $height (optional) specifies a height for the placement
     * @param int $transparency (optional) specifies the transparency of the placed image, in percentage
     * @return $this
     */
    public function compositeImage($image, $coordX, $coordY, $width = 0, $height = 0, $transparency = 0)
    {
        $this->resource->compositeImage($image, $coordX, $coordY, $width, $height, $transparency);

        return $this;
    }

    /**
     * Places an image on top of the current resource. If the width and height are supplied,
     * will perform a resize before placing the image.
     *
     * @param mixed $image Path to an image on filesystem or an Imanee Object
     * @param int $place_constant One of the Imanee::IM_POS constants, defaults to IM_POS_TOP_LEFT (top left corner)
     * @param int $width (optional) specifies a width for the placement
     * @param int $height (optional) specifies a height for the placement
     * @param int $transparency (optional) specifies the transparency of the placed image.
     * 0 for fully opaque (default), 100 for fully transparent
     *
     * @return $this
     */
    public function placeImage(
        $image,
        $place_constant = Imanee::IM_POS_TOP_LEFT,
        $width = null,
        $height = null,
        $transparency = 0
    ) {
        $this->resource->placeImage($image, $place_constant, $width, $height, $transparency);

        return $this;
    }

    /**
     * Convenient method to place a watermark image on top of the current resource
     *
     * @param mixed $image The path to the watermark image file or an Imanee object
     * @param int $place_constant One of the Imanee::IM_POS constants, defaults to IM_POS_BOTTOM_RIGHT
     * @param int $transparency Watermark transparency percentage. Defaults to 0 (fully opaque)
     *
     * @return $this
     */
    public function watermark($image, $place_constant = Imanee::IM_POS_BOTTOM_RIGHT, $transparency = 0)
    {
        $this->resource->placeImage($image, $place_constant, 0, 0, $transparency);

        return $this;
    }

    /**
     * Rotates the image resource in the given degrees
     *
     * @param float $degrees Degrees to rotate the image. Negative values will rotate the image anti-clockwise
     * @param string $background Background to fill the empty spaces, default is transparent -
     * will render as black for jpg format (use png if you want it transparent)
     *
     * @return $this
     */
    public function rotate($degrees, $background = 'transparent')
    {
        $this->resource->rotate($degrees, $background);

        return $this;
    }

    /**
     * Crops a portion of the image
     *
     * @param int $width The width
     * @param int $height The height
     * @param int $coordX The X coordinate
     * @param int $coordY The Y coordinate
     *
     * @return $this
     */
    public function crop($width, $height, $coordX, $coordY)
    {
        $this->resource->crop($width, $height, $coordX, $coordY);

        return $this;
    }

    /**
     * Creates a thumbnail of the current resource. If crop is true, the result will be a perfect fit thumbnail with the
     * given dimensions, cropped by the center. If crop is false, the thumbnail will use the best fit for the dimensions
     *
     * @param int $width Width of the thumbnail
     * @param int $height Height of the thumbnail
     * @param bool $crop When set to true, the thumbnail will be cropped from the center to match the given size
     *
     * @return $this
     */
    public function thumbnail($width, $height, $crop = false)
    {
        $this->resource->thumbnail($width, $height, $crop);

        return $this;
    }

    /**
     * Gets the width of the current image resource
     *
     * @return int the width
     */
    public function getWidth()
    {
        return $this->resource->getWidth();
    }

    /**
     * Gets the height of the current image resource
     *
     * @return int the height
     */
    public function getHeight()
    {
        return $this->resource->getHeight();
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

    /**
     * Convenient way to output the image
     * @return string
     */
    public function __toString()
    {
        return $this->output();
    }

    /**
     * Saves the image to disk. If the second param is provided, will try to compress the image using JPEG compression.
     *
     * The format will be decided based on the extension used for the filename. If, for instance,
     * a "img.png" is provided, the image will be saved as PNG and the compression will not take affect.
     *
     * @param string $path The file path to save the image
     * @param int $jpeg_quality (optional) the quality for JPEG files, 1 to 100 where 100 means no compression
     * (higher quality and bigger file)
     *
     * @return Imanee $this
     */
    public function write($path, $jpeg_quality = null)
    {
        $this->resource->write($path, $jpeg_quality);

        return $this;
    }

    /**
     * Gets the Imagick Resource from the Image Object
     *
     * @return \Imagick
     */
    public function getIMResource()
    {
        return $this->resource->getResource();
    }

    /**
     * Sets the Imagick resource in the Image Object
     * @param \Imagick $imagick
     * @return $this
     */
    public function setIMResource(\Imagick $imagick)
    {
        $this->resource->setResource($imagick);

        return $this;
    }

    /**
     * Gets the default filters
     *
     * @return array Return an array with the default filters
     */
    public function getFilters()
    {
        return [
            new ModulateFilter(),
            new BWFilter(),
            new SepiaFilter(),
            new ColorFilter(),
        ];
    }

    /**
     * Adds a custom filter to the FilterResolver
     *
     * @param FilterInterface $filter The Filter
     */
    public function addFilters(FilterInterface $filter)
    {
        $this->filterResolver->addFilter($filter);
    }

    /**
     * Tries to apply the specified filter to the current resource
     * @param string $filter The filter identifier, e.g. "filter_bw"
     * @param array $options
     * @throws FilterNotFoundException
     * @return $this
     */
    public function applyFilter($filter, array $options = [])
    {
        $filter = $this->filterResolver->resolve($filter);

        if (!$filter) {
            throw new FilterNotFoundException();
        }

        $this->resource->applyFilter($filter, $options);

        return $this;
    }

    /**
     * Convenient method for generating text-only images
     *
     * @param string $text
     * @param Drawer $drawer
     * @param string $format
     * @param string $background
     * @return Imanee
     */
    public static function textGen($text, Drawer $drawer = null, $format = 'png', $background = 'transparent')
    {
        $imanee = new Imanee();

        if ($drawer !== null) {
            $imanee->setDrawer($drawer);
        }

        $size = $imanee->resource->getTextGeometry($text, $imanee->getDrawer());
        $imanee->newImage($size['width'], $size['height'], $background);
        $imanee->setFormat($format);

        $imanee->placeText($text, Imanee::IM_POS_TOP_LEFT);

        return $imanee;
    }
    
    /**
     * Adds a frame for generating animated gifs with the animate() method
     * @param Imanee $imanee
     */
    public function addFrame(Imanee $imanee)
    {
        $this->frames[] = $imanee;
    }

    /**
     * @param int $delay
     * @return string
     */
    public function animate($delay = 20)
    {
        $gif = new \Imagick();
        $gif->setFormat('gif');

        foreach ($this->frames as $imanee) {
            $frame = $imanee->getIMResource();
            $frame->setImageDelay($delay);
            $gif->addImage($frame);
        }

        return (new Imanee())
            ->setIMResource($gif)
            ->setFormat('gif');
    }

    /**
     * Convenient method for generating an animated gif from an array of images.
     *
     * @param array $images
     * @param int $delay
     * @return string
     */
    public static function arrayAnimate(array $images, $delay = 20)
    {
        $gif = new \Imagick();
        $gif->setFormat('gif');

        foreach ($images as $image) {
            $frame = new \Imagick($image);
            $frame->setImageDelay($delay);
            $gif->addImage($frame);
        }

        return (new Imanee())
            ->setIMResource($gif)
            ->setFormat('gif');
    }

    /**
     * Convenient method for generating an animated gif from image files in a directory.
     *
     * @param $pattern
     * @param int $delay
     * @return string
     */
    public static function globAnimate($pattern, $delay = 20)
    {
        $gif = new \Imagick();
        $gif->setFormat('gif');

        foreach (glob($pattern) as $image) {
            $frame = new \Imagick($image);
            $frame->setImageDelay($delay);
            $gif->addImage($frame);
        }

        return (new Imanee())
            ->setIMResource($gif)
            ->setFormat('gif');
    }

    /**
     * Helper method to get info about an image saved in disk
     * @param string $imagePath
     * @return array Array containing the keys 'mime', 'width' and 'height'
     * @throws ImageNotFoundException
     */
    public static function getImageInfo($imagePath)
    {
        if (!is_file($imagePath)) {
            throw new ImageNotFoundException(
                sprintf("File '%s' not found. Are you sure this is the right path?", $imagePath)
            );
        }

        $info = getimagesize($imagePath);

        return [
            'mime' => $info['mime'],
            'width' => $info[0],
            'height' => $info[1],
        ];
    }
}
