<?php
/**
 * DrawerTest
 */

namespace Imanee\Tests;


use Imanee\Drawer;

class DrawerTest extends \PHPUnit_Framework_TestCase
{
    protected $model;

    public function setup()
    {
        $this->model = new Drawer();
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testConstructorSetsDefaults()
    {
        $this->assertEquals(22, $this->model->getFontSize());
        $this->assertEquals('#000000', $this->model->getFontColor());
    }

    public function testShouldSetAndGetFontSize()
    {
        $this->model->setFontSize(20);

        $this->assertEquals(20, $this->model->getFontSize());
    }

    public function testShouldSetAndGetFont()
    {
        $this->model->setFont('Arial.ttf');

        $this->assertEquals('Arial.ttf', $this->model->getFont());
    }

    public function testShouldSetAndGetFontColor()
    {
        $this->model->setFontColor('Black');

        $this->assertEquals('Black', $this->model->getFontColor());
    }

    public function testShouldSetAndGetTextAlign()
    {
        $this->model->setTextAlign(Drawer::TEXT_ALIGN_RIGHT);

        $this->assertEquals(Drawer::TEXT_ALIGN_RIGHT, $this->model->getTextAlign());
    }
}
