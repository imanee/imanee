<?php

namespace Imanee\Model;

use Imanee\Imanee;

/**
 * Creates animations for an image.
 */
interface ImageAnimatableInterface
{
    /**
     * Animates an array of frames containing images.
     *
     * @param mixed $frames Collection of Imanee objects, or a string array with paths to images.
     * @param int   $delay
     *
     * @return Imanee Returns a new Imanee object that can be outputted or written to disk.
     */
    public function animate(array $frames, $delay = 20);
}
