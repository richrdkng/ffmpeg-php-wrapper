<?php namespace FFMPEGWrapper\Option;

use FFMPEGWrapper\FFMPEGLogLevel;

class LogLevelOption extends FFMPEGOption {

    /** @var int */
    private $_level;

    /**
     * LogLevelOption constructor.
     *
     * @param int $level
     */
    public function __construct($level = FFMPEGLogLevel::DEFAULT_LEVEL)
    {
        $this->_level = $level;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        return "-loglevel {$this->_level}";
    }
}
