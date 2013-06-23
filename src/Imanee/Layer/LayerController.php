<?php
/**
 Imanee LayerController
 */

namespace Imanee\Layer;


use Imanee\Layer;

class LayerController {

    protected $layer_collection;

    public function __construct()
    {
        $this->addLayer();
    }

    function addLayer(Layer $layer)
    {
        $this->layer_collection[] = $layer;
    }

    function addTextLayer()
    {

    }
}