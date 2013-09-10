<?php

namespace Imanee;

/**
 * Class Drawer
 * Saves Drawing settings
 * @package Imanee
 */
class Drawer extends ConfigContainer {

    private $drawer;

    const TEXT_ALIGN_LEFT   = 1;
    const TEXT_ALIGN_CENTER = 2;
    const TEXT_ALIGN_RIGHT  = 3;

    public function __construct(array $values = [])
    {
        $this->drawer = new \ImagickDraw();

        parent::__construct($values, [
                'font'  => 'Bookman-Demi',
                'size'  => 22,
                'color' => '#000000',
                'align' => Drawer::TEXT_ALIGN_LEFT
            ]);
    }

    public function prepare()
    {
        $this->drawer->setFont($this->font);
        $this->drawer->setfillcolor($this->color);
        $this->drawer->setfontsize($this->size);
        $this->drawer->settextalignment($this->align);
    }

    public function getDrawer()
    {
        $this->prepare();

        return $this->drawer;
    }

    public function setFontSize($size)
    {
        $this->size = $size;
    }

    public function setTextAlign($align)
    {
        $this->align = $align;
    }
}