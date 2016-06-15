<?php namespace FFMPEGWrapper\Option;

use FFMPEGWrapper\FFMPEG;

abstract class FFMPEGOption {

    /**
     * @param FFMPEG $ffmpeg
     *
     * @return void
     */
    public function check(FFMPEG $ffmpeg)
    {
        // noop
    }

    /**
     * @return string
     */
    public function toFFMPEGArgOption()
    {
        return "";
    }

    /**
     * @return array
     */
    public function toFFMPEGEnvOption()
    {
        return [];
    }
}
