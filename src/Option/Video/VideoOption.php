<?php

namespace FFMPEGWrapper\Option\Video;

use FFMPEGWrapper\Option\FFMPEGOption;
use FFMPEGWrapper\Option\Video\Bitrate\VideoBitrateOption;
use FFMPEGWrapper\Option\Video\Codec\VideoCodecOption;

class VideoOption extends FFMPEGOption {

    const NONE = 1;
    const COPY = 2;

    /** @var VideoCodecOption|int */
    private $_codec;

    /** @var  VideoBitrateOption|null */
    private $_bitrate = null;

    /**
     * VideoOption constructor.
     *
     * @param VideoCodecOption|int|boolean $codec
     * @param VideoBitrateOption|null      $bitrate
     *
     * @throws \Error
     */
    public function __construct($codec, $bitrate = null)
    {
        if ($codec instanceof VideoCodecOption) {
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
                    throw new \Error("Unknown video codec: \"{$codec}\"");
            }
        }

        if ($bitrate instanceof VideoBitrateOption) {
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
            $option = "-vn";

        } else if ($this->_isCopy()) {
            $option = "-c:v copy";

        } else {
            $option = $this->_codec->toFFMPEGVideoArgOption();

            if ($this->_bitrate !== null) {
                $option .= " " . $this->_bitrate->toFFMPEGVideoBitrateArgOption();
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
