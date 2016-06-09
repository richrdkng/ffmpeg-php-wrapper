<?php namespace FFMPEGWrapper\Option;

class InputSeekOption extends FFMPEGOption {

    private $_time;

    /**
     * InputSeekOption constructor.
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
        return "-ss " . $this->_time;
    }
}
