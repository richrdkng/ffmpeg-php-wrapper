<?php namespace FFMPEGWrapper\Option;

class InputOption extends FFMPEGOption {

    private $_path;

    /**
     * InputOption constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->_path = $path;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        return "-i " . $this->_path;
    }
}
