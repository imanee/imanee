<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erika
 * Date: 6/18/13
 * Time: 8:45 PM
 * To change this template use File | Settings | File Templates.
 */
include __DIR__ . '/../vendor/autoload.php';

use Imanee\Imanee;

class ImaneeTest extends PHPUnit_Framework_TestCase {

    protected $test_jpg;

    public function setup()
    {
        $this->test_jpg = __DIR__ . '/resources/img01.jpg';
    }

    public function testConstruct()
    {
        //$im = new Imanee();
        //$im->load($this->test_jpg)->resize(200,200)->output();

        /*$img = (new Imanee())
        ->setSize(640, 480)
        ->setBackground(\Imanee\Image::COLOR_BLACK)
        ->writeText('test', [0, 0]);


        $img = (new Imanee())->setSize(320, 240)->writeText("test", [0,0])->save('test.png');

        $img['filters']->addFilter(\Imanee\Image::FILTER_BW);
        $img['layers']->addLayer($img1);
        $img['frames']->addFrame((new \Imanee\Imanee())->setSize(640, 480));

        $img = (new Imanee())->setSize(320, 240)->getDrawer()->writeText("test", [0,0])->save('test.png');


        $img = (new \Imanee\Imanee())
            ->setSize()
            ->setBackground()
            ->setDrawer((new \Imanee\Drawer())->set('background_color', 0)->set('stroke', 1))
            ->writeText();
        */
        $this->assertNotNull('foo');
    }

    public function testCreateJpg()
    {
        $im = new Imanee($this->test_jpg);

        $this->assertSame('image/jpeg', $im->getMime());
    }


}
