<?php namespace FFMPEGWrapper\Option;

class OverwriteOutputOption extends FFMPEGOption {

    private $_overwriteOutput;

    /**
     * OverwriteOutputOption constructor.
     *
     * @param boolean $overwriteOutput
     */
    public function __construct($overwriteOutput = true)
    {
        $this->_overwriteOutput = $overwriteOutput;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        if ($this->_overwriteOutput) {
            return "-y";
        }

        return "";
    }
}
