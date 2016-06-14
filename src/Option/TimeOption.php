<?php

namespace FFMPEGWrapper\Option;

class TimeOption extends FFMPEGOption {

    /** @var string */
    private $_time;

    /**
     * TimeOption constructor.
     *
     * @param string $time Time in HH:MM:SS format.
     */
    public function __construct($time)
    {
        $this->_time = $time;
    }

    public function getTime()
    {
        return $this->_time;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        return "-t {$this->_time}";
    }
}
