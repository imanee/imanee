<?php

namespace Imanee;

use Imanee\Exception\FilterNotFoundException;
use Imanee\Exception\ImageNotFoundException;
use Imanee\Exception\UnsupportedFormatException;
use Imanee\Exception\UnsupportedMethodException;
use Imanee\Filter\BWFilter;
use Imanee\Filter\ColorFilter;
use Imanee\Filter\ModulateFilter;
use Imanee\Filter\SepiaFilter;
use Imanee\Filter\GaussianFilter;
use Imanee\Model\ImageAnimatableInterface;
use Imanee\Model\ImageComposableInterface;
use Imanee\Model\ImageFilterableInterface;
use Imanee\Model\ImageResourceInterface;
use Imanee\Model\ImageWritableInterface;
use Imanee\Model\FilterInterface;

class Imanee
{
    /**
     * @var ImageResourceInterface
     */
    protected $resource;

    /**
     * The drawer settings
     *
     * @var Drawer
     */
    protected $drawer;

    /**
     * @var array
     */
    protected $frames;

    /**
     * The filter Resolver.
     *
     * @var FilterResolver
     */
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
     * @param null|string                 $path     Path to a image file, for opening an image
     *                                              without using the load() method.
     * @param null|ImageResourceInterface $resource A valid object implementing the
     *                                              ImageResourceInterface; defaults to null, in
     *                                              which case a resource will be automatically
     *                                              created based on current extensions available.
     */
    public function __construct($path = null, ImageResourceInterface $resource = null)
    {
        $this->drawer = new Drawer();

        if (!$resource) {
            $provider = new ResourceProvider(new PhpExtensionAvailabilityChecker());
            $resource = $provider->createImageResource();
        }

        $this->resource = $resource;
        if ($this->resource instanceof ImageFilterableInterface) {
            $this->filterResolver = new FilterResolver($this->resource->loadFilters());
        }

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
     * Loads an image from a file.
     *
     * @param string $imagePath The path to the image.
     *
     * @return $this
     */
    public function load($imagePath)
    {
        $this->resource->load($imagePath);

        return $this;
    }

    /**
     * Creates a new "blank" image.
     *
     * @param int    $width The width of the image.
     * @param int    $height The height of the image.
     * @param string $background The image background.
     *
     * @return $this
     */
    public function newImage($width, $height, $background = 'white')
    {
        $this->resource->createNew($width, $height, $background);

        return $this;
    }

    /**
     * Gets the mime type associated with the current resource (if available).
     *
     * @return string The mime type.
     */
    public function getMime()
    {
        return $this->resource->getMime();
    }

    /**
     * Sets the format to the current loaded resource.
     *
     * @param string $format The image format, e.g: "jpeg".
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->resource->setFormat($format);

        return $this;
    }

    /**
     * Gets the current format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->resource->getFormat();
    }

    /**
     * Resizes the current image resource.
     *
     * @param int  $width   The new width.
     * @param int  $height  The new height.
     * @param bool $bestfit When set to true, will fit the image inside the provided box dimensions.
     *                      When set to false, will force resize to the specified dimensions, which
     *                      may cause the resulting image to be out of proportion.
     * @param bool $stretch When set to false, an image smaller than the box area won't be scaled up
     *                      to meet the desired size. Defaults to true
     *
     * @return $this
     */
    public function resize($width, $height, $bestfit = true, $stretch = true)
    {
        if (!$stretch and (($width >= $this->getWidth()) and ($height >= $this->getHeight()))) {
            return $this;
        }

        $this->resource->resize($width, $height, $bestfit, $stretch);

        return $this;
    }

    /**
     * Rotates the image resource in the given degrees.
     *
     * @param float  $degrees    Degrees to rotate the image. Negative values will rotate the image
     *                           anti-clockwise.
     * @param string $background Background to fill the empty spaces. Will render as black for jpg
     *                           format (use png for transparency).
     *
     * @return $this
     */
    public function rotate($degrees, $background = 'transparent')
    {
        $this->resource->rotate($degrees, $background);

        return $this;
    }

    /**
     * Crops a portion of the image.
     *
     * @param int $width  The width.
     * @param int $height The height.
     * @param int $coordX The X coordinate.
     * @param int $coordY The Y coordinate.
     *
     * @return $this
     */
    public function crop($width, $height, $coordX, $coordY)
    {
        $this->resource->crop($width, $height, $coordX, $coordY);

        return $this;
    }

    /**
     * Creates a thumbnail of the current resource. If crop is true, the result will be a perfect
     * fit thumbnail with the given dimensions, cropped by the center. If crop is false, the
     * thumbnail will use the best fit for the dimensions.
     *
     * @param int  $width   Width of the thumbnail.
     * @param int  $height  Height of the thumbnail.
     * @param bool $crop    When set to true, the thumbnail will be cropped from the center to match
     *                      the given size.
     * @param bool $stretch When set to false, an image smaller than the box area won't be scaled up
     *                      to meet the desired size. Defaults to true
     *
     * @return $this
     */
    public function thumbnail($width, $height, $crop = false, $stretch = true)
    {
        if (!$stretch and (($width >= $this->getWidth()) and ($height >= $this->getHeight()))) {
            return $this;
        }

        $this->resource->thumbnail($width, $height, $crop);

        return $this;
    }

    /**
     * Gets the width of the current image resource.
     *
     * @return int The width.
     */
    public function getWidth()
    {
        return $this->resource->getWidth();
    }

    /**
     * Gets the height of the current image resource.
     *
     * @return int The height.
     */
    public function getHeight()
    {
        return $this->resource->getHeight();
    }

    /**
     * Shortcut method to get width and height.
     */
    public function getSize()
    {
        return ['width' => $this->getWidth(), 'height' => $this->getHeight()];
    }

    /**
     * Output the current image resource as a string.
     *
     * @param string $format The image format (overwrites the currently defined format).
     *
     * @return string The image data as a string.
     */
    public function output($format = null)
    {
        return $this->resource->output($format);
    }

    /**
     * Convenient way to output the image.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->output();
    }

    /**
     * Saves the image to disk. If the second param is provided, will try to compress the image
     * using JPEG compression.
     *
     * The format will be decided based on the extension used for the filename. If, for instance,
     * a "img.png" is provided, the image will be saved as PNG and the compression will not take
     * affect.
     *
     * @param string $path         The file path to save the image.
     * @param int    $jpeg_quality The quality for JPEG files, 1 to 100 where 100 means no
     *                             compression (higher quality and bigger file).
     *
     * @return $this
     */
    public function write($path, $jpeg_quality = null)
    {
        $this->resource->write($path, $jpeg_quality);

        return $this;
    }

    /**
     * Gets the current image resource.
     *
     * @return ImageResourceInterface
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Sets the current Image Resource.
     *
     * @param ImageResourceInterface $resource
     */
    public function setResource(ImageResourceInterface $resource)
    {
        $this->resource = $resource;

        if ($this->resource instanceof ImageFilterableInterface) {
            $this->filterResolver = new FilterResolver($this->resource->loadFilters());
        }
    }

    /**
     * @return FilterResolver
     */
    public function getFilterResolver()
    {
        return $this->filterResolver;
    }

    /**
     * Adjusts the font size of the Drawer object to fit a text in the desired width.
     *
     * @param string $text
     * @param Drawer $drawer
     * @param int    $width
     *
     * @return Drawer
     *
     * @throws UnsupportedMethodException
     */
    public function adjustFontSize($text, Drawer $drawer, $width)
    {
        if (! ($this->resource instanceof ImageWritableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        $fontSize = 0;
        $metrics['width'] = 0;

        while ($metrics['width'] <= $width) {
            $drawer->setFontSize($fontSize);
            $metrics = $this->resource->getTextGeometry($text, $drawer);
            $fontSize++;
        }

        return $drawer;
    }

    /**
     * Places a text on top of the current image, for writing text using relative positioning. To
     * overwrite the current Drawer settings, create a custom Drawer object and use the setDrawer()
     * method before.
     *
     * @param string $text           Text to be written.
     * @param int    $place_constant One of the Imanee:IM_POS constants.
     * @param int    $fitWidth       If a positive value is provided, will change the font size to
     *                               fit the text in this width.
     * @param int $fontSize          The font size. Defaults to the current font size defined in the
     *                               Drawer.
     *
     * @return $this
     *
     * @throws UnsupportedMethodException
     */
    public function placeText($text, $place_constant = Imanee::IM_POS_TOP_LEFT, $fitWidth = 0, $fontSize = 0)
    {
        if (! ($this->resource instanceof ImageWritableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        if ($fontSize) {
            $this->getDrawer()->setFontSize($fontSize);
        }

        if ($fitWidth > 0) {
            $this->setDrawer($this->adjustFontSize($text, $this->getDrawer(), $fitWidth));
        }

        list ($coordX, $coordY) = PixelMath::getPlacementCoordinates(
            $this->resource->getTextGeometry($text, $this->getDrawer()),
            $this->getSize(),
            $place_constant
        );

        $this->resource->annotate(
            $text,
            $coordX,
            $coordY + $this->resource->getFontSize($this->getDrawer()),
            0,
            $this->getDrawer()
        );

        return $this;
    }

    /**
     * Writes text to an image.
     *
     * @param string $text   The text to be written.
     * @param int    $coordX The X coordinate for text placement.
     * @param int    $coordY The Y coordinate for text placement.
     * @param int    $size   The font size.
     * @param int    $angle  The angle.
     *
     * @return $this
     * @throws UnsupportedMethodException
     */
    public function annotate($text, $coordX, $coordY, $size = null, $angle = 0)
    {
        if (! ($this->resource instanceof ImageWritableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        $drawer = $this->getDrawer();

        if ($size) {
            $drawer->setFontSize($size);
        }
        $this->resource->annotate($text, $coordX, $coordY, $angle, $drawer);

        return $this;
    }

    /**
     * Sets the drawer. Use this to change the default text settings.
     *
     * @param Drawer $drawer
     *
     * @return $this
     */
    public function setDrawer(Drawer $drawer)
    {
        $this->drawer = $drawer;

        return $this;
    }

    /**
     * Gets the current drawer in use.
     *
     * @return Drawer
     */
    public function getDrawer()
    {
        return $this->drawer;
    }

    /**
     * @param mixed $image        Path to an image on filesystem or an Imanee Object.
     * @param int   $coordX       X coordinate for placement.
     * @param int   $coordY       Y coordinate for placement.
     * @param int   $width        Width for the placement.
     * @param int   $height       Height for the placement.
     * @param int   $transparency Transparency of the placed image, in percentage.
     *
     * @return $this
     *
     * @throws UnsupportedMethodException
     */
    public function compositeImage($image, $coordX, $coordY, $width = 0, $height = 0, $transparency = 0)
    {
        if (! ($this->resource instanceof ImageComposableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        $this->resource->compositeImage($image, $coordX, $coordY, $width, $height, $transparency);

        return $this;
    }

    /**
     * Places an image on top of the current resource. If the width and height are supplied, will
     * perform a resize before placing the image.
     *
     * @param mixed $image          Path to an image on filesystem or an Imanee Object.
     * @param int   $place_constant One of the Imanee::IM_POS constants, defaults to
     *                              IM_POS_TOP_LEFT (top left corner).
     * @param int   $width          Width for the placement.
     * @param int   $height         Height for the placement.
     * @param int $transparency     Transparency of the placed image - 0 (default) to 100
     *                              (transparent).
     *
     * @return $this
     *
     * @throws UnsupportedMethodException
     * @throws UnsupportedFormatException
     */
    public function placeImage(
        $image,
        $place_constant = Imanee::IM_POS_TOP_LEFT,
        $width = null,
        $height = null,
        $transparency = 0
    ) {
        if (! ($this->resource instanceof ImageComposableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        if (!is_object($image)) {
            $img = clone $this;
            $img->load($image);
            $image = $img;
        }

        if (! ($image instanceof \Imanee\Imanee)) {
            throw new UnsupportedFormatException('Object not supported. It must be an instance of Imanee');
        }

        if ($width and $height) {
            $image->resize($width, $height);
        }

        list ($coordX, $coordY) = PixelMath::getPlacementCoordinates(
            $image->getSize(),
            ['width' => $this->getWidth(), 'height' => $this->getHeight()],
            $place_constant
        );

        $this->resource->compositeImage($image, $coordX, $coordY, 0, 0, $transparency);

        return $this;
    }

    /**
     * Convenient method to place a watermark image on top of the current resource.
     *
     * @param mixed $image          The path to the watermark image file or an Imanee object.
     * @param int   $place_constant One of the Imanee::IM_POS constants
     * @param int   $transparency   Watermark transparency percentage.
     *
     * @return $this
     */
    public function watermark($image, $place_constant = Imanee::IM_POS_BOTTOM_RIGHT, $transparency = 0)
    {
        $this->placeImage($image, $place_constant, 0, 0, $transparency);

        return $this;
    }

    /**
     * Gets loaded filters.
     *
     * @return array Returns an array with the current loaded filters.
     *
     * @throws UnsupportedMethodException
     */
    public function getFilters()
    {
        if (! ($this->resource instanceof ImageFilterableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        return $this->getFilterResolver()->getFilters();
    }

    /**
     * Adds a custom filter to the FilterResolver.
     *
     * @param FilterInterface $filter The Filter
     *
     * @return $this
     *
     * @throws UnsupportedMethodException
     */
    public function addFilter(FilterInterface $filter)
    {
        if (! ($this->resource instanceof ImageFilterableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        $this->getFilterResolver()->addFilter($filter);

        return $this;
    }

    /**
     * Tries to apply the specified filter to the current resource.
     *
     * @param string $filter  The filter identifier, e.g. "filter_bw".
     * @param array  $options
     *
     * @return $this
     *
     * @throws FilterNotFoundException
     * @throws UnsupportedMethodException
     */
    public function applyFilter($filter, array $options = [])
    {
        if (! ($this->resource instanceof ImageFilterableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        $filter = $this->getFilterResolver()->resolve($filter);

        if (!$filter) {
            throw new FilterNotFoundException();
        }

        $filter->apply($this, $options);

        return $this;
    }

    /**
     * Shortcut for adding filters.
     *
     * @param array $frames Array of Imanee objects or image paths (string), or both.
     *
     * @return $this
     */
    public function addFrames(array $frames)
    {
        foreach ($frames as $frame) {
            $this->addFrame($frame);
        }

        return $this;
    }

    /**
     * Adds a frame for generating animated GIFs with the animate() method.
     *
     * @param mixed $frame A string with a file path or an Imanee object.
     *
     * @return $this
     */
    public function addFrame($frame)
    {
        $this->frames[] = $frame;

        return $this;
    }

    /**
     * @return Imanee[]
     */
    public function getFrames()
    {
        return $this->frames;
    }
    /**
     * Removes a frame from a list used for generating animated Gifs.
     *
     * @param  int $offset
     * @throws \InvalidArgumentException
     *
     * @return Imanee
     */
    public function removeFrame($offset)
    {
        if (!isset($this->frames[$offset])) {
            throw new \InvalidArgumentException('Offset does not exist.');
        }

        unset($this->frames[$offset]);

        return $this;
    }
    /**
     * Provides a new Imanee object with frames retrieved from a gif
     *
     * @return Imanee
     */
    public function getGifFrames()
    {
        return $this->resource->getGifFrames();
    }

    /**
     * @param int $delay
     *
     * @return string
     *
     * @throws UnsupportedMethodException
     */
    public function animate($delay = 20)
    {
        if (! ($this->resource instanceof ImageAnimatableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        return $this->resource->animate($this->getFrames(), $delay);
    }

    /**
     * Generates text-only images.
     *
     * @param string $text
     * @param Drawer $drawer
     * @param string $format
     * @param string $background
     *
     * @return Imanee
     */
    public static function textGen(
        $text,
        Drawer $drawer = null,
        $format = 'png',
        $background = 'transparent',
        ImageResourceInterface $resource = null
    ) {
        $imanee = new Imanee(null, $resource);

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
     * Generates an animated gif from an array of images.
     *
     * @param array $images Array containing paths to the images that should be used as frames.
     * @param int   $delay
     *
     * @return string
     *
     * @throws UnsupportedMethodException
     */
    public static function arrayAnimate(array $images, $delay = 20)
    {
        $imanee = new Imanee();

        if (! ($imanee->resource instanceof ImageAnimatableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        return $imanee
           ->resource
           ->animate($images, $delay);
    }

    /**
     * Generates an animated gif from image files in a directory.
     *
     * @param string $pattern
     * @param int    $delay
     *
     * @return string
     *
     * @throws UnsupportedMethodException
     */
    public static function globAnimate($pattern, $delay = 20)
    {
        $imanee = new Imanee();

        if (! ($imanee->resource instanceof ImageAnimatableInterface)) {
            throw new UnsupportedMethodException("This method is not supported by the ImageResource in use.");
        }

        $frames = [];

        foreach (glob($pattern) as $image) {
            $frames[] = $image;
        }

        return $imanee
            ->resource
            ->animate($frames, $delay);
    }

    /**
     * Get info about an image saved in disk.
     *
     * @param string $imagePath
     *
     * @return array Array containing the keys 'mime', 'width' and 'height'.
     *
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
