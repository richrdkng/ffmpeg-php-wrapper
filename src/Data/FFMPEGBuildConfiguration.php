<?php

namespace FFMPEGWrapper\Data;

class FFMPEGBuildConfiguration {    
    use FFMPEGDataGetterTrait;

    private $_name;

    public function __construct($name)
    {
        $this->_name = $name;

        $this->addToGetterArray([
            "name" => "getName"
        ]);
    }

    public function getName()
    {
        return $this->_name;
    }
}
