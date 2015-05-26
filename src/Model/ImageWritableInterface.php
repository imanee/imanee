<?php
/**
 * Image: Writable
 * Class which implement this interface should be able to handle text writing
 */

namespace Imanee\Model;

use Imanee\Drawer;

interface ImageWritableInterface
{
    /**
     * Places a text on top of the current image resource using relative positioning and the provided Drawer object.
     * If fitWidth is provided, will calculate the correct font size to fit in the provided width.
     *
     * @param string $text           The text to be written.
     * @param int    $place_constant Where to place the image - one of the \Imanee:IM_POS constants
     * @param Drawer $drawer         The drawer object
     * @param int    $fitWidth       If provided and different than zero, will calculate a new font size
     * to fit text in the provided width
     */
    public function placeText($text, $place_constant, Drawer $drawer, $fitWidth = 0);

    /**
     * Writes text on the current image resource
     *
     * @param string $text
     * @param int    $coordX
     * @param int    $coordY
     * @param int    $angle
     * @param Drawer $drawer
     */
    public function annotate($text, $coordX, $coordY, $angle, Drawer $drawer);

    /**
     * Gets the size of a text, given the text and the \Imanee\Drawer object
     *
     * @param string $text   The text
     * @param Drawer $drawer The Drawer object
     * @return array
     */
    public function getTextGeometry($text, Drawer $drawer);
}
