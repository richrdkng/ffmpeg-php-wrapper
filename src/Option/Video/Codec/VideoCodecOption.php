<?php

namespace FFMPEGWrapper\Option\Video\Codec;

abstract class VideoCodecOption {

    /**
     * @return string
     */
    abstract function toFFMPEGVideoArgOption();
}
