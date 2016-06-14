<?php

namespace FFMPEGWrapper\Option;

class InputSeekOption extends FFMPEGOption {

    /** @var string */
    private $_time;

    /**
     * InputSeekOption constructor.
     *
     * @param string $time Time in HH:MM:SS format.
     */
    public function __construct($time)
    {
        $this->_time = $time;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->_time;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        return "-ss {$this->_time}";
    }
}
