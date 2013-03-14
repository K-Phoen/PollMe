<?php

namespace Rock\Tests\Collections;

use Rock\Collections\Map;


class MapTest extends \PHPUnit_Framework_TestCase
{
    protected $map;

    protected function setUp()
    {
        $this->map = new Map();
    }


    public function testAdd()
    {
        $this->assertSame(array(), $this->map->all());

        $this->map->add(array(
            'foo' => 'bar',
            'bar' => 'baz'
        ));
        $this->assertSame(array('foo' => 'bar', 'bar' => 'baz'), $this->map->all());
    }

    public function testAll()
    {
        $this->assertSame(array(), $this->map->all());

        $this->map->set('foo', 'bar');
        $this->assertSame(array('foo' => 'bar'), $this->map->all());

        $this->map->set('bar', 'baz');
        $this->assertSame(array('foo' => 'bar', 'bar' => 'baz'), $this->map->all());
    }

    public function testConstructor()
    {
        $this->assertSame(array(), $this->map->all());

        $this->map = new Map(array(
            'foo' => 'bar',
            'bar' => 'baz'
        ));
        $this->assertSame(array('foo' => 'bar', 'bar' => 'baz'), $this->map->all());
    }

    public function testGet()
    {
        $this->assertNull($this->map->get('foo'));
        $this->assertEquals(42, $this->map->get('foo', 42));

        $this->map->set('foo', 'bar');
        $this->assertEquals('bar', $this->map->get('foo'));
    }

    public function testHas()
    {
        $this->assertFalse($this->map->has('foo'));

        $this->map->set('foo', 'bar');
        $this->assertTrue($this->map->has('foo'));
    }
}

