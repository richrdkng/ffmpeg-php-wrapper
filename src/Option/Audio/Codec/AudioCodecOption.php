<?php

namespace FFMPEGWrapper\Option\Audio\Codec;

abstract class AudioCodecOption {

    /**
     * @return string
     */
    abstract function toFFMPEGAudioArgOption();
}
