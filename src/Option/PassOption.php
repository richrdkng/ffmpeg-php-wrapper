<?php namespace FFMPEGWrapper\Option;

class PassOption extends FFMPEGOption {

    private $_passNumber;

    private $_forceFormat;

    private $_passLogFile;

    /**
     * PassOption constructor.
     *
     * @param int $passNumber
     * @param string|null $forceFormat
     * @param string|null $passLogFile
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

        $option .= " -an -y /dev/null";

        return $option;
    }
}
