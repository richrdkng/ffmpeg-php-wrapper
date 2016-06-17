<?php

namespace FFMPEGWrapper\Option\Video;

use FFMPEGWrapper\Option\CodecOption;
use FFMPEGWrapper\Option\Video\Bitrate\VideoBitrateOption;
use FFMPEGWrapper\Option\Video\Codec\VideoCodecOption;

class VideoOption extends CodecOption {

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
        parent::__construct(CodecOption::CODEC);

        if ($codec instanceof VideoCodecOption) {
            $this->_codec = $codec;

        } else {
            switch ($codec) {
                case CodecOption::NONE:
                case CodecOption::COPY:
                    $this->_codec = $codec;
                    break;

                case false:
                    $this->_codec = CodecOption::NONE;
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

        if ($this->isDisabled()) {
            $option = "-vn";

        } else if ($this->isCopy()) {
            $option = "-c:v copy";

        } else {
            $option = $this->_codec->toFFMPEGVideoArgOption();

            if ($this->_bitrate !== null) {
                $option .= " " . $this->_bitrate->toFFMPEGVideoBitrateArgOption();
            }
        }

        return $option;
    }

    private function isCopy()
    {
        return $this->_codec === CodecOption::COPY;
    }

    private function isDisabled()
    {
        return $this->_codec === CodecOption::NONE;
    }
}
