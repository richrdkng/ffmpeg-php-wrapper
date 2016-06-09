<?php

namespace FFMPEGWrapper\Option\Audio;

use FFMPEGWrapper\Option\Audio\Bitrate\AudioBitrateOption;
use FFMPEGWrapper\Option\Audio\Codec\AudioCodecOption;
use FFMPEGWrapper\Option\FFMPEGOption;

class AudioOption extends FFMPEGOption {

    const NONE = 1;
    const COPY = 2;

    /** @var AudioCodecOption|int */
    private $_codec;

    /** @var AudioBitrateOption|null */
    private $_bitrate = null;

    /**
     * VideoOption constructor.
     *
     * @param AudioCodecOption|int|boolean $codec
     * @param AudioBitrateOption|null      $bitrate
     *
     * @throws \Error
     */
    public function __construct($codec, $bitrate = null)
    {
        if ($codec instanceof AudioCodecOption) {
            $this->_codec = $codec;

        } else {
            switch ($codec) {
                case self::NONE:
                case self::COPY:
                    $this->_codec = $codec;
                    break;

                case false:
                    $this->_codec = self::NONE;
                    break;

                default:
                    throw new \Error("Unknown audio codec: \"{$codec}\"");
            }
        }

        if ($bitrate instanceof AudioBitrateOption) {
            $this->_bitrate = $bitrate;
        }
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        $option = "";

        if ($this->_isDisabled()) {
            $option = "-an";

        } else if ($this->_isCopy()) {
            $option = "-c:a copy";

        } else {
            $option = $this->_codec->toFFMPEGAudioArgOption();

            if ($this->_bitrate !== null) {
                $option .= " " . $this->_bitrate->toFFMPEGAudioBitrateArgOption();
            }
        }

        return $option;
    }

    private function _isCopy()
    {
        return $this->_codec === self::COPY;
    }

    private function _isDisabled()
    {
        return $this->_codec === self::NONE;
    }
}
