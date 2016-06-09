<?php

namespace FFMPEGWrapper\Option\Audio\Bitrate;

class CBRAudioBitrateOption extends AudioBitrateOption {

    private $_bitrate;

    public function __construct($bitrate)
    {
        $this->_bitrate = $bitrate;
    }

    /**
     * @return string
     */
    function toFFMPEGAudioBitrateArgOption()
    {
        return "-b:a {$this->_bitrate}";
    }
}
