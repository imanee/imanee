<?php
/**
 * ConfigContainer Abstract Class Test
 */

namespace Imanee\Tests;

class ConfigContainerTest extends \PHPUnit_Framework_TestCase
{
    protected $model;

    public function setup()
    {
        $this->model = $this->getMockForAbstractClass('Imanee\ConfigContainer');
    }

    public function tearDown()
    {
        $this->model = null;
    }

    public function testConstructorShouldSetDefaults()
    {
        $defaults = [
            'option1' => 'value1',
            'option2' => 'value2',
        ];

        $values = [
            'option1' => 'newValue',
            'option3' => 'value3',
        ];

        $config = $this->getMockForAbstractClass('Imanee\ConfigContainer');

        $config->__construct($defaults, $values);

        $this->assertEquals($config->option1, 'newValue');
        $this->assertEquals($config->option2, 'value2');
        $this->assertEquals($config->option3, 'value3');
    }

    public function testShouldSetAndGetConfigValuesMagicMethods()
    {
        $this->model->myCustomValue = 'myValue';

        $this->assertEquals('myValue', $this->model->myCustomValue);
    }

    public function testShouldSetAndGetConfigValues()
    {
        $this->model->set('myCustomValue', 'myValue');

        $this->assertEquals('myValue', $this->model->get("myCustomValue"));
    }
}
