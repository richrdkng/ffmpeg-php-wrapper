<?php namespace FFMPEGWrapper\Option;

class ReportOption extends FFMPEGOption {

    /** @var string|null */
    private $_file;

    /**
     * ReportOption constructor.
     *
     * @param string|null $file
     */
    public function __construct($file = null)
    {
        $this->_file = $file;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        $option = "-report";

        if ($this->_file !== null) {
            $option .= " \"{$this->_file}\"";
        }

        return $option;
    }
}
