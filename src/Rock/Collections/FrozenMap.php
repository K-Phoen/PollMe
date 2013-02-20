<?php

namespace Rock\Collections;


class FrozenMap
{
    protected $map;


    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->map[$key] : $default;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->map);
    }
}
