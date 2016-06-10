<?php

namespace FFMPEGWrapper\Data;

class FFMPEGCodec {

    const DECODING_SUPPORTED     = 1 << 0;
    const ENCODING_SUPPORTED     = 1 << 1;
    const VIDEO_CODEC            = 1 << 2;
    const AUDIO_CODEC            = 1 << 3;
    const SUBTITLE_CODEC         = 1 << 4;
    const INTRA_FRAME_ONLY_CODEC = 1 << 5;
    const LOSSY_COMPRESSION      = 1 << 6;
    const LOSSLESS_COMPRESSION   = 1 << 7;

    private static $__pattern = "/(?<flags>[\w\.]+)\s+(?<name>[\w\-\,]+)\s+(?<desc>[\w\s()\/\-\.,:'\"]+)/";

    public static function match($string)
    {
        return preg_match(self::$__pattern, $string) === 1;
    }

    public static function from($string)
    {
        preg_match(self::$__pattern, $string, $matches);

        $name = $matches["name"];
        $desc = $matches["desc"];

        $flags = $matches["flags"];

        $matchedFlags = 0;

        if (strpos($flags, "D") === 0) {
            $matchedFlags |= self::DECODING_SUPPORTED;
        }

        if (strpos($flags, "E") === 1) {
            $matchedFlags |= self::ENCODING_SUPPORTED;
        }

        if (strpos($flags, "V") === 2) {
            $matchedFlags |= self::VIDEO_CODEC;
        }

        if (strpos($flags, "A") === 2) {
            $matchedFlags |= self::AUDIO_CODEC;
        }

        if (strpos($flags, "S") === 2) {
            $matchedFlags |= self::SUBTITLE_CODEC;
        }

        if (strpos($flags, "I") === 3) {
            $matchedFlags |= self::INTRA_FRAME_ONLY_CODEC;
        }

        if (strpos($flags, "L") === 4) {
            $matchedFlags |= self::LOSSY_COMPRESSION;
        }

        if (strpos($flags, "S") === 5) {
            $matchedFlags |= self::LOSSLESS_COMPRESSION;
        }

        return new self($name, $desc, $matchedFlags);
    }

    private $_name;

    private $_description;

    private $_decodingSupported = false;
    private $_encodingSupported = false;
    private $_videoCodec = false;
    private $_audioCodec = false;
    private $_subtitleCodec = false;
    private $_intraFrameOnlyCodec = false;
    private $_lossyCompression = false;
    private $_losslessCompression = false;

    public function __construct($name, $description, $flags)
    {
        $this->_name        = $name;
        $this->_description = $description;

        if ($flags & self::DECODING_SUPPORTED) {
            $this->_decodingSupported = true;
        }

        if ($flags & self::ENCODING_SUPPORTED) {
            $this->_encodingSupported = true;
        }

        if ($flags & self::VIDEO_CODEC) {
            $this->_videoCodec = true;
        }

        if ($flags & self::AUDIO_CODEC) {
            $this->_audioCodec = true;
        }

        if ($flags & self::SUBTITLE_CODEC) {
            $this->_subtitleCodec = true;
        }

        if ($flags & self::INTRA_FRAME_ONLY_CODEC) {
            $this->_intraFrameOnlyCodec = true;
        }

        if ($flags & self::LOSSY_COMPRESSION) {
            $this->_lossyCompression = true;
        }

        if ($flags & self::LOSSLESS_COMPRESSION) {
            $this->_losslessCompression = true;
        }
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function isDecodingSupported()
    {
        return $this->_decodingSupported;
    }

    public function isEncodingSupported()
    {
        return $this->_encodingSupported;
    }

    public function isVideoCodec()
    {
        return $this->_videoCodec;
    }

    public function isAudioCodec()
    {
        return $this->_audioCodec;
    }

    public function isSubtitleCodec()
    {
        return $this->_subtitleCodec;
    }

    public function isIntraFrameOnlyCodec()
    {
        return $this->_intraFrameOnlyCodec;
    }

    public function lossyCompression()
    {
        return $this->_lossyCompression;
    }

    public function losslessCompression()
    {
        return $this->_losslessCompression;
    }

    public function getFlags()
    {
        $flag = 0;

        if ($this->isDecodingSupported()) {
            $flag |= self::DECODING_SUPPORTED;
        }

        if ($this->isEncodingSupported()) {
            $flag |= self::ENCODING_SUPPORTED;
        }

        if ($this->isVideoCodec()) {
            $flag |= self::VIDEO_CODEC;
        }

        if ($this->isAudioCodec()) {
            $flag |= self::AUDIO_CODEC;
        }

        if ($this->isSubtitleCodec()) {
            $flag |= self::SUBTITLE_CODEC;
        }

        if ($this->isIntraFrameOnlyCodec()) {
            $flag |= self::INTRA_FRAME_ONLY_CODEC;
        }

        if ($this->lossyCompression()) {
            $flag |= self::LOSSY_COMPRESSION;
        }

        if ($this->losslessCompression()) {
            $flag |= self::LOSSLESS_COMPRESSION;
        }

        return $flag;
    }
}
