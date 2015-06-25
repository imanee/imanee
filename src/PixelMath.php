<?php
/**
 * Helper class for pixel / coordinate calculations
 */

namespace Imanee;

/**
 * Performs pixel/coordinate calculations.
 */
class PixelMath
{
    /**
     * Gets the best fit for a given width / height where the provided values will be used as
     * maximum values (the resulting image won't ever pass these dimensions).
     *
     * @param int $width
     * @param int $height
     * @param int $originalWidth
     * @param int $originalHeight
     *
     * @return array
     */
    public static function getBestFit($width, $height, $originalWidth, $originalHeight)
    {
        $finalWidth = $width;
        $finalHeight = ($finalWidth * $originalHeight) / $originalWidth;

        if ($finalHeight > $height) {
            $finalHeight = $height;
            $finalWidth = ($finalHeight * $originalWidth) / $originalHeight;
        }

        return ['width' => $finalWidth, 'height' =>$finalHeight];
    }

    /**
     * Gets the best fit for a given width / height where the provided values will be used as
     * minimum values (the resulting image can be bigger, there won't be any blank spaces).
     *
     * @param int $width
     * @param int $height
     * @param int $originalWidth
     * @param int $originalHeight
     *
     * @return array
     */
    public static function getMaxFit($width, $height, $originalWidth, $originalHeight)
    {
        $finalWidth = $width;
        $finalHeight = ($finalWidth * $originalHeight) / $originalWidth;

        if ($finalHeight < $height) {
            $finalHeight = $height;
            $finalWidth = ($finalHeight * $originalWidth) / $originalHeight;
        }

        return ['width' => $finalWidth, 'height' =>$finalHeight];
    }

    /**
     * Gets the coordinates for a relative placement using the IM_POS constants.
     *
     * @param array $resourceSize   An array with the keys 'width' and 'height' from the image to be
     *                              placed.
     * @param array $size           An array with they keys 'width' and 'height' from the original,
     *                              base image.
     * @param int   $place_constant An Imanee::IM_POS_* constant.
     *
     * @return int[] Returns an array with the first position representing the X coordinate and the
     *               second position representing the Y coordinate for placing the image.
     */
    public static function getPlacementCoordinates(
        $resourceSize = [],
        $size = [],
        $place_constant = Imanee::IM_POS_TOP_LEFT
    ) {
        $x = 0;
        $y = 0;

        switch ($place_constant) {

            case Imanee::IM_POS_TOP_CENTER:
                $x = ($size['width'] / 2) - ($resourceSize['width'] / 2);
                break;

            case Imanee::IM_POS_TOP_RIGHT:
                $x = ($size['width']) - ($resourceSize['width']);
                break;

            case Imanee::IM_POS_MID_LEFT:
                $y = ($size['height'] / 2) - ($resourceSize['height'] / 2);
                break;

            case Imanee::IM_POS_MID_CENTER:
                $x = ($size['width'] / 2) - ($resourceSize['width'] / 2);
                $y = ($size['height'] / 2) - ($resourceSize['height'] / 2);
                break;

            case Imanee::IM_POS_MID_RIGHT:
                $x = ($size['width']) - ($resourceSize['width']);
                $y = ($size['height'] / 2) - ($resourceSize['height'] / 2);
                break;

            case Imanee::IM_POS_BOTTOM_LEFT:
                $y = ($size['height']) - ($resourceSize['height']);
                break;

            case Imanee::IM_POS_BOTTOM_CENTER:
                $x = ($size['width'] / 2) - ($resourceSize['width'] / 2);
                $y = ($size['height']) - ($resourceSize['height']);
                break;

            case Imanee::IM_POS_BOTTOM_RIGHT:
                $x = ($size['width']) - ($resourceSize['width']);
                $y = ($size['height']) - ($resourceSize['height']);
                break;
        }

        return [
            (int) floor($x),
            (int) floor($y)
        ];
    }
}
