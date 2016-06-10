<?php

namespace FFMPEGWrapper\Status;

final class FFMPEGStatus {

    const STARTED     = 1;
    const IN_PROGRESS = 2;
    const ENDED       = 3;

    /** @var FFMPEGStatusStruct */
    private $_struct;

    public static function from(FFMPEGStatusStruct $struct)
    {
        return new self($struct);
    }

    private function __construct(FFMPEGStatusStruct $struct)
    {
        $this->_struct = $struct;
    }

    public function getTotalDuration()
    {
        return $this->_struct->totalDuration;
    }

    public function getSelectedDuration()
    {
        return $this->_struct->selectedDuration;
    }

    public function getCurrentTime()
    {
        return $this->_struct->currentTime;
    }

    public function getCurrentFrame()
    {
        return $this->_struct->currentFrame;
    }

    public function getCurrentPercent()
    {
        if ($this->isEnded()) {
            return 1;
        }

        return $this->_struct->currentPercent;
    }

    public function getETA()
    {
        return $this->_struct->ETA;
    }

    public function isStarted()
    {
        return $this->_struct->isStarted;
    }

    public function isProgress()
    {
        return $this->_struct->isProgress;
    }

    public function isEnded()
    {
        return $this->_struct->isEnded;
    }

    public function getStatus()
    {
        if ($this->isStarted()) {
            return self::STARTED;

        } else if ($this->isEnded()) {
            return self::ENDED;

        } else {
            return self::IN_PROGRESS;
        }
    }

    public function getMediaDescription()
    {
        if ($this->isStarted()) {
            return $this->_struct->mediaDescription;
        }

        return "";
    }

    public function getMediaEndStats()
    {
        if ($this->isEnded()) {
            return $this->_struct->mediaEndStats;
        }

        return "";
    }
}
