<?php

namespace FFMPEGWrapper\Option\Video;

use FFMPEGWrapper\Option\FFMPEGOption;

class VideoSizeOption extends FFMPEGOption {

    const _1080p = "1920x1080";

    const _720p  = "1280x720";

    const _480p  = "852x480";

    const _360p  = "640×360";

    const _240p  = "426×240";

    const _144p  = "256×144";

    private $_size;

    public function __construct($size)
    {
        $this->_size = $size;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        return "-size {$this->_size}";
    }
}
