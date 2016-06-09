<?php namespace FFMPEGWrapper\Option;

class TimeOption extends FFMPEGOption {

    private $_time;

    /**
     * TimeOption constructor.
     *
     * @param string $time
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
        return "-t " . $this->_time;
    }
}
