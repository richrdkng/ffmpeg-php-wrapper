<?php

namespace FFMPEGWrapper\Data;

class FFMPEGLibrary {
    use FFMPEGDataGetterTrait;

    private $_name;
    private $_version;

    public function __construct($name, $version)
    {
        $this->_name    = $name;
        $this->_version = $version;

        $this->_addToGetterArray([
            "name"    => "getName",
            "version" => "getVersion"
        ]);
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getVersion()
    {
        return $this->_version;
    }
}
