<?php

namespace FFMPEGWrapper\Option\Audio\Bitrate;

abstract class AudioBitrateOption {

    /**
     * @return string
     */
    abstract function toFFMPEGAudioBitrateArgOption();
}
