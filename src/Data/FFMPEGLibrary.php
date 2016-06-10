<?php

namespace FFMPEGWrapper\Data;

class FFMPEGLibrary {

    private $_name;
    private $_version;

    public function __construct($name, $version)
    {
        $this->_name    = $name;
        $this->_version = $version;
    }
}
