<?php

namespace FFMPEGWrapper\Status;

final class FFMPEGStatusStruct {

    public $totalDuration    = null;
    public $selectedDuration = null;
    public $currentTime      = null;
    public $currentFrame     = null;
    public $currentPercent   = null;
    public $ETA              = null;

    public $isStarted  = false;
    public $isProgress = false;
    public $isEnded    = false;

    public $mediaDescription = null;
    public $mediaEndStats    = null;
}
