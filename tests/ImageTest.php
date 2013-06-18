<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erika
 * Date: 6/18/13
 * Time: 8:43 PM
 * To change this template use File | Settings | File Templates.
 */
include __DIR__ . '/../vendor/autoload.php';

class ImageTest extends PHPUnit_Framework_TestCase{

    public function testConstruct()
    {
        $this->assertNotNull('foo');
    }
}