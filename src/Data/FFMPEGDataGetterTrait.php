<?php

namespace FFMPEGWrapper\Data;

trait FFMPEGDataGetterTrait {

    private $_getterArray = [];

    private function _addToGetterArray(array $keyValues)
    {
        foreach ($keyValues as $key => $value) {
            $this->_getterArray[$key] = $value;
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_getterArray)) {
            $name = $this->_getterArray[$name];
            return $this->$name();
        }

        return null;
    }
}
