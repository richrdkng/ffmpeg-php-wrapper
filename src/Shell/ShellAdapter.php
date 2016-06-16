<?php

namespace FFMPEGWrapper\Shell;

use FFMPEGWrapper\FFMPEG;

abstract class ShellAdapter
{
    /**
     * @param FFMPEG      $ffmpeg
     *
     * @return string
     */
    abstract public function toShellCommand(FFMPEG $ffmpeg);
}
