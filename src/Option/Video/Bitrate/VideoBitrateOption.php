<?php

namespace FFMPEGWrapper\Option\Video\Bitrate;

abstract class VideoBitrateOption {

    /**
     * @return string
     */
    abstract function toFFMPEGVideoBitrateArgOption();
}
