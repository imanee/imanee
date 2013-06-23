<?php
/**
 * Imanee Layer
 */

namespace Imanee;


abstract class Layer extends ConfigContainer {

    public function __construct(array $values = [])
    {
        parent::__construct($values, [
            'width'  => 100,
            'height' => 100,
            'x'      => 0,
            'y'      => 0,
        ]);

        return $this;
    }

    abstract function draw();
}