<?php

namespace roulette\Helpers;

trait Castable
{
    public function cast($object)
    {
        if (is_array($object) || is_object($object)) {
            foreach ($object as $key => $value) {
                $this->$key = $value;
            }
        }
        return $this;
    }
}