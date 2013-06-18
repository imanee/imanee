<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erika
 * Date: 6/18/13
 * Time: 10:02 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Imanee;

/**
 * Class Drawer
 * Saves Drawing settings
 * @package Imanee
 */
class Drawer extends ConfigContainer {

    public function __construct(array $values = [])
    {
        parent::__construct($values, [
                'font_face'        => 'Arial',
                'font_size'        => 22,
                'foreground_color' => '#000000',
            ]);
    }
}