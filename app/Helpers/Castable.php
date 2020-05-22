<?php

namespace roulette\Helpers;

trait Castable
{

    /**
     * быстрое создание объекта нужного класса из массива или объекта стандартного класса
     * @param array|object $object
     * @return $this
     */
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