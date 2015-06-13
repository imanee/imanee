<?php
/**
 * Image: Animatable
 * Classes which implements this interface should be able to handle animated gifs (multiple frames)
 */

namespace Imanee\Model;

use Imanee\Imanee;

interface ImageAnimatableInterface
{
    /**
     * Animates an array of frames containing images
     * @param mixed $frames - Can be either a collection of Imanee objects, or a string array with paths to images,
     * or both mixed
     * @param int $delay
     * @return Imanee - a new Imanee object that can be outputted or written to disk
     */
    public function animate(array $frames, $delay = 20);
}
