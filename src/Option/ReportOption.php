<?php namespace FFMPEGWrapper\Option;

class ReportOption extends FFMPEGOption {

    /** @var string|null */
    private $_file;

    /** @var int|null */
    private $_logLevel;

    /**
     * ReportOption constructor.
     *
     * @param string|null $file
     * @param int|null    $logLevel
     */
    public function __construct($file = null, $logLevel = null)
    {
        $this->_file     = $file;
        $this->_logLevel = $logLevel;
    }

    /**
     * @return string
     */
    public function toFFMPEGArgOption()
    {
        return "-report";
    }

    /**
     * @return array
     */
    public function toFFMPEGEnvOption()
    {
        if ($this->_file !== null) {
            $file     = "file={$this->_file}";
            $logLevel = "";

            if ($this->_logLevel !== null) {
                $logLevel = ":level={$this->_logLevel}";
            }

            return [
                "FFREPORT" => "{$file}{$logLevel}"
            ];
        }

        return [];
    }
}
