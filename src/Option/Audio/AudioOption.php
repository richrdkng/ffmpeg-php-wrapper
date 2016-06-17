<?php

namespace FFMPEGWrapper\Option\Audio;

use FFMPEGWrapper\Option\Audio\Bitrate\AudioBitrateOption;
use FFMPEGWrapper\Option\Audio\Codec\AudioCodecOption;
use FFMPEGWrapper\Option\CodecOption;

class AudioOption extends CodecOption {

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
     * @throws \Exception
     */
    public function __construct($codec, $bitrate = null)
    {
        parent::__construct(CodecOption::CODEC);

        if ($codec instanceof AudioCodecOption) {
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
                    throw new \Exception("Unknown audio codec: \"{$codec}\"");
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

        if ($this->isDisabled()) {
            $option = "-an";

        } else if ($this->isCopy()) {
            $option = "-c:a copy";

        } else {
            $option = $this->_codec->toFFMPEGAudioArgOption();

            if ($this->_bitrate !== null) {
                $option .= " " . $this->_bitrate->toFFMPEGAudioBitrateArgOption();
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
