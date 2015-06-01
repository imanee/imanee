<?php
/**
 * Image: Animatable
 * Classes which implements this interface should be able to handle animated gifs (multiple frames)
 */

namespace Imanee\Model;


interface ImageAnimatableInterface
{
    /**
     * @param mixed $frames - Can be either a collection of Imanee objects, or a string array with paths to images,
     * or both mixed
     * @param int $delay
     * @return $this
     */
    public function animate(array $frames, $delay = 20);
}
