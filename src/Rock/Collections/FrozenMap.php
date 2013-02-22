<?php

namespace Rock\Collections;


class FrozenMap extends Map
{
    public function set($key, $value)
    {
        throw new \BadMethodCallException('Method not available on a frozen map');
    }
}
