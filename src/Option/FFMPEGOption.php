<?php namespace FFMPEGWrapper\Option;

use FFMPEGWrapper\FFMPEG;

abstract class FFMPEGOption {

    /**
     * @param FFMPEG $ffmpeg
     *
     * @return void
     */
    abstract function check(FFMPEG $ffmpeg);

    /**
     * @return string
     */
    abstract function toFFMPEGArgOption();
}
