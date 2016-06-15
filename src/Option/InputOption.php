<?php namespace FFMPEGWrapper\Option;

use FFMPEGWrapper\Exception\FFMPEGNoSuchFileOrDirectoryException;
use FFMPEGWrapper\FFMPEG;

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

    function check(FFMPEG $ffmpeg)
    {
        if (! file_exists($this->_path) || ! is_file($this->_path)) {
            throw new FFMPEGNoSuchFileOrDirectoryException($this->_path);
        }

    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        return "-i \"{$this->_path}\"";
    }
}
