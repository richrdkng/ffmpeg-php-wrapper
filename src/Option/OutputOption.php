<?php

namespace FFMPEGWrapper\Option;

use FFMPEGWrapper\FFMPEG;

class OutputOption extends FFMPEGOption {

    /** @var string */
    private $_path;

    /** @var bool */
    private $_overwriteFile;

    /**
     * OutputOption constructor.
     *
     * @param string $path
     * @param bool   $overwriteFile
     */
    public function __construct($path, $overwriteFile = true)
    {
        $this->_path          = $path;
        $this->_overwriteFile = $overwriteFile;
    }

    /**
     * @return string
     */
    public function toFFMPEGArgOption()
    {
        $option = "\"{$this->_path}\"";

        if ($this->_overwriteFile) {
            $option = "-y {$option}";
        }

        return $option;
    }
}
