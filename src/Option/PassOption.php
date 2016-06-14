<?php

namespace FFMPEGWrapper\Option;

class PassOption extends FFMPEGOption {

    /** @var int */
    private $_passNumber;

    /** @var null|string */
    private $_forceFormat;

    /** @var null|string */
    private $_passLogFile;

    /**
     * PassOption constructor.
     *
     * @param int         $passNumber
     * @param null|string $forceFormat
     * @param null|string $passLogFile
     */
    public function __construct($passNumber, $forceFormat = null, $passLogFile = null)
    {
        $this->_passNumber  = $passNumber;
        $this->_forceFormat = $forceFormat;
        $this->_passLogFile = $passLogFile;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        $option = "-pass {$this->_passNumber}";

        if ($this->_forceFormat !== null) {
            $option .= " -f {$this->_forceFormat}";
        }

        if ($this->_passLogFile !== null) {
            $option .= " -passlogfile \"{$this->_passLogFile}\"";
        }

        if ($this->_passNumber == 1) {
            $option .= " -an -y /dev/null";
        }

        return $option;
    }
}
