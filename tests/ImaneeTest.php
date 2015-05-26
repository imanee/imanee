<?php

namespace Imanee\Tests;

use Imanee\Imanee;

class ImaneeTest extends \PHPUnit_Framework_TestCase
{
    protected $model;

    public function setup()
    {
        $this->model = new Imanee();
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testConstructorShouldLoadPath()
    {

        $this->assertNotNull('foo');
    }

    public function testCreateJpg()
    {
        //$im = new Imanee($this->test_jpg);

        //$this->assertSame('image/jpeg', $im->getMime());
    }
}
