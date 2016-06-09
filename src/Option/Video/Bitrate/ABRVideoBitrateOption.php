<?php

namespace FFMPEGWrapper\Option\Video\Bitrate;

class ABRVideoBitrateOption extends VideoBitrateOption {

    private $_averageBitrate;
    private $_maxBitrate;

    public function __construct($averageBitrate, $maxBitrate = null)
    {
        $this->_averageBitrate = $averageBitrate;
        $this->_maxBitrate     = $maxBitrate;
    }

    /**
     * @return string
     */
    function toFFMPEGVideoBitrateArgOption()
    {
        $option = "-b:v {$this->_averageBitrate}";

        if ($this->_maxBitrate !== null) {
            $option .= " -maxrate {$this->_maxBitrate}";
        }

        return $option;
    }
}
