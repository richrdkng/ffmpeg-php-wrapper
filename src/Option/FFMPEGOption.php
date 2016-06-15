<?php namespace FFMPEGWrapper\Option;

use FFMPEGWrapper\FFMPEG;

abstract class FFMPEGOption {

    /**
     * @param FFMPEG $ffmpeg
     *
     * @return void
     */
    public function check(FFMPEG $ffmpeg) {
        // noop
    }

    /**
     * @return string
     */
    abstract function toFFMPEGArgOption();
}
