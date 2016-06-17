<?php

namespace FFMPEGWrapper\Option\Video;

use FFMPEGWrapper\Option\FFMPEGOption;

class VideoSizeOption extends FFMPEGOption {

    const _1080P = "1920x1080";

    const _720P  = "1280x720";

    const _480P  = "852x480";

    const _360P  = "640×360";

    const _240P  = "426×240";

    const _144P  = "256×144";

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
