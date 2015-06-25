<?php
/**
 * GDPixel Helper to translate colors like ImagickPixel
 * Colors are treated as html string by default as they have to be separated into R G B
 */

namespace Imanee\ImageResource;

use Imanee\Exception\InvalidColorException;

class GDPixel
{
    public $color;
    public $channelR;
    public $channelG;
    public $channelB;

    protected $colors;

    /**
     * Returns a color representation for GD based on a color string
     * Examples of valid color strings:
     *    - black
     *    - white
     *    - purple
     *    - #00FFCC
     *    - 00FFCC
     * @param string $color
     * @param resource $resource
     * @return int
     *
     * Current color aliases: check loadColorAliases()
     */
    public static function load($color, $resource)
    {
        if ($color === 'transparent') {

            return imagecolorallocatealpha($resource, 0, 0, 0, 127);
        }

        $gdpixel = new GDPixel($color);

        return imagecolorallocate($resource, $gdpixel->channelR, $gdpixel->channelG, $gdpixel->channelB);
    }

    /**
     * @param string $color
     * @throws InvalidColorException
     */
    public function __construct($color)
    {
        $this->loadColorAliases();

        //is one of the color alias?
        if (array_key_exists(strtolower($color), $this->colors)) {
            $color = $this->colors[$color];
        }

        //is it html hexa?
        if ((strpos($color, '#')) !== false) {
            $color = str_replace('#', '', $color);
        }

        if (!preg_match('/^[a-f0-9]{6}$/i', $color)) {
            throw new InvalidColorException(
                sprintf('Color \'%s\' is not supported use a HEX color or the name of a common color', $color)
            );
        }

        //now we should have something like 000000
        $this->channelR = hexdec(substr($color, 0, 2));
        $this->channelG = hexdec(substr($color, 2, 2));
        $this->channelB = hexdec(substr($color, 4, 2));
    }

    /**
     * Loads string aliases for common colors. The colors where picked based on ImageMagick (same shades)
     */
    public function loadColorAliases()
    {
        $this->colors = [
            'black'    => '000000',
            'white'    => 'FFFFFF',
            'grey'     => 'BEBEBE',
            'red'      => 'FE0000',
            'green'    => '008001',
            'blue'     => '0000FE',
            'purple'   => '81007F',
            'pink'     => '#FFC0CB',
            'yellow'   => 'FFFF00',
            'orange'   => 'FEA500',
            'silver'   => 'C0C0C0',
            'lavender' => 'E5E6FA',
            'salmon'   => 'FA8071',
            'magenta'  => '#FF00FE',
            'plum'     => '#DDA0DC',
        ];
    }
}
