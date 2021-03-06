<?php

namespace Rock\Collections;


class Map
{
    protected $map;


    public function __construct(array $map = array())
    {
        $this->map = $map;
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->map[$key] : $default;
    }

    public function set($key, $value)
    {
        return $this->map[$key] = $value;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->map);
    }

    public function all()
    {
        return $this->map;
    }

    public function add(array $values)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }
}
