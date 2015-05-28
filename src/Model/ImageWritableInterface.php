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
     * @return array Array containing the indexes 'width' and 'height' representing the dimensions this text
     * would have based on the provided Drawer object.
     */
    public function getTextGeometry($text, Drawer $drawer);


    /**
     * Returns the adjusted font size - this is important as Imagick and GD have different standards. To keep the
     * size identical some adjustments are necessary.
     * @param Drawer $drawer
     * @return mixed
     */
    public function getFontSize(Drawer $drawer);
}
