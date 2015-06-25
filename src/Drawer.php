<?php

namespace Imanee;

/**
 * Saves Drawing settings.
 */
class Drawer extends ConfigContainer
{
    const TEXT_ALIGN_LEFT   = 1;
    const TEXT_ALIGN_CENTER = 2;
    const TEXT_ALIGN_RIGHT  = 3;

    /**
     * @var string
     */
    public static $DEFAULT_FONT = 'OpenSans-Regular.ttf';

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $defaultFont = __DIR__ . '/Resources/'. self::$DEFAULT_FONT;

        parent::__construct($values, [
            'font'  => $defaultFont,
            'size'  => 22,
            'color' => '#000000',
            'align' => Drawer::TEXT_ALIGN_LEFT,
        ]);
    }

    /**
     * Sets the font size.
     *
     * @param int $size
     *
     * @return $this
     */
    public function setFontSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Gets the font size.
     *
     * @return int The font size.
     */
    public function getFontSize()
    {
        return $this->size;
    }

    /**
     * Sets the font.
     *
     * @param string $font The name/path to the font.
     *
     * @return $this
     */
    public function setFont($font)
    {
        $this->font = $font;

        return $this;
    }

    /**
     * Gets the current font.
     *
     * @return string The font name/path.
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * Sets the font color.
     *
     * @param mixed $color Any color format accepted by Imagick, e.g.: 'black', '#000000' or an
     *                     ImagickPixel object.
     *
     * @return $this
     */
    public function setFontColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Gets the current font color.
     *
     * @return mixed
     */
    public function getFontColor()
    {
        return $this->color;
    }

    /**
     * Sets the text align.
     *
     * @param int $align Drawer:TEXT_ALIGN_LEFT, Drawer:TEXT_ALIGN_CENTER or
     *                   Drawer:TEXT_ALIGN_RIGHT.
     *
     * @return $this
     */
    public function setTextAlign($align)
    {
        $this->align = (int) $align;

        return $this;
    }

    /**
     * Gets the current text align.
     *
     * @return int Number representing the current Text Align constant applied.
     */
    public function getTextAlign()
    {
        return $this->align;
    }
}
