<?php

namespace FFMPEGWrapper\Option;

class CodecOption extends FFMPEGOption {

    const NONE = 1;

    const COPY = 2;

    const CODEC = 3;

    /** @var int */
    private $_codec;

    /**
     * CodecOption constructor.
     *
     * @param int $codec
     */
    public function __construct($codec)
    {
        $this->_codec = $codec;
    }

    /**
     * @return string
     */
    function toFFMPEGArgOption()
    {
        $option = "-codec ";

        switch ($this->_codec) {
            case self::NONE:
                $option .= "none";
                break;

            case self::COPY:
                $option .= "copy";
                break;
        }

        return $option;
    }
}
