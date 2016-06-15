<?php

namespace FFMPEGWrapper\Option\Video\Codec;

abstract class VideoCodecOption {

    /**
     * @return string
     */
    public function toFFMPEGVideoArgOption()
    {
        return "";
    }
}
