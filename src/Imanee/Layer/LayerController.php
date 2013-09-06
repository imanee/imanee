<?php
/**
 Imanee LayerController
 */

namespace Imanee\Layer;


use Imanee\Layer;

class LayerController {

    protected $layer_collection;

    function addLayer(Layer $layer)
    {
        $this->layer_collection[] = $layer;
    }

}