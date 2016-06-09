<?php namespace FFMPEGWrapper\Option;

class OutputOption extends FFMPEGOption {

    private $_path;

    private $_overwrite;

    /**
     * OutputOption constructor.
     *
     * @param string $path
     * @param bool $overwrite
     */
    public function __construct($path, $overwrite = true)
    {
        $this->_path = $path;
        $this->_overwrite = $overwrite;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        if ($this->_overwrite) {
            return "-y {$this->_path}";
        }

        return "{$this->_path}";
    }
}
