<?php namespace FFMPEGWrapper\Option;

class LogLevelOption extends FFMPEGOption {

    const QUIET   = -8;

    const PANIC   = 0;

    const FATAL   = 8;

    const ERROR   = 16;

    const WARNING = 24;

    const INFO    = 32;

    const VERBOSE = 40;

    const DEBUG   = 48;

    const TRACE   = 56;

    const DEFAULT_LEVEL = self::VERBOSE;

    /** @var int */
    private $_level;

    /**
     * LogLevelOption constructor.
     *
     * @param int $level
     */
    public function __construct($level = self::DEFAULT_LEVEL)
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
