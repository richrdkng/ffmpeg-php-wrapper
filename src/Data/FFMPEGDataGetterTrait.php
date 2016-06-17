<?php

namespace FFMPEGWrapper\Data;

trait FFMPEGDataGetterTrait {

    private $getterArray = [];

    private function addToGetterArray(array $keyValues)
    {
        foreach ($keyValues as $key => $value) {
            $this->getterArray[$key] = $value;
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->getterArray)) {
            $name = $this->getterArray[$name];
            return $this->$name();
        }

        return null;
    }
}
