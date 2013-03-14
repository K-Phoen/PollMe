<?php

namespace Rock\Tests\Collections;

use Rock\Collections\FrozenMap;


class FrozenMapTest extends \PHPUnit_Framework_TestCase
{
    protected $map;

    protected function setUp()
    {
        $this->map = new FrozenMap(array(
            'foo' => 'bar',
            'bar' => 'baz'
        ));
    }


    public function testAll()
    {
        $this->assertSame(array('foo' => 'bar', 'bar' => 'baz'), $this->map->all());
    }

    public function testGet()
    {
        $this->assertNull($this->map->get('toto'));
        $this->assertEquals(42, $this->map->get('toto', 42));

        $this->assertEquals('bar', $this->map->get('foo'));
    }

    public function testHas()
    {
        $this->assertFalse($this->map->has('toto'));
        $this->assertTrue($this->map->has('foo'));
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSet()
    {
        $this->map->set('bar', 'baz');
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testAdd()
    {
        $this->map->add(array(
            'foo' => 'bar',
            'bar' => 'baz'
        ));
    }
}
