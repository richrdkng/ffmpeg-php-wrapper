<?php

namespace FFMPEGWrapper\Data;

class FFMPEGEncoderDecoder {
    use FFMPEGDataGetterTrait;

    const VIDEO                              = 1 << 0;
    const AUDIO                              = 1 << 1;
    const SUBTITLE                           = 1 << 2;
    const FRAME_LEVEL_MULTITHREADING         = 1 << 3;
    const SLICE_LEVEL_MULTITHREADING         = 1 << 4;
    const EXPERIMENTAL                       = 1 << 5;
    const SUPPORTS_DRAW_HORIZ_BAND           = 1 << 6;
    const SUPPORTS_DIRECT_RENDERING_METHOD_1 = 1 << 7;

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

        if (strpos($flags, "V") === 0) {
            $matchedFlags |= self::VIDEO;
        }

        if (strpos($flags, "A") === 0) {
            $matchedFlags |= self::AUDIO;
        }

        if (strpos($flags, "S") === 0) {
            $matchedFlags |= self::SUBTITLE;
        }

        if (strpos($flags, "F") === 1) {
            $matchedFlags |= self::FRAME_LEVEL_MULTITHREADING;
        }

        if (strpos($flags, "S") === 2) {
            $matchedFlags |= self::SLICE_LEVEL_MULTITHREADING;
        }

        if (strpos($flags, "X") === 3) {
            $matchedFlags |= self::EXPERIMENTAL;
        }

        if (strpos($flags, "B") === 4) {
            $matchedFlags |= self::SUPPORTS_DRAW_HORIZ_BAND;
        }

        if (strpos($flags, "D") === 5) {
            $matchedFlags |= self::SUPPORTS_DIRECT_RENDERING_METHOD_1;
        }

        return new self($name, $desc, $matchedFlags);
    }

    private $_name;

    private $_description;

    private $_video = false;
    private $_audio = false;
    private $_subtitle = false;
    private $_frameLevelMultithreading = false;
    private $_sliceLevelMultithreading = false;
    private $_experimental = false;
    private $_supportsDrawHorizBand = false;
    private $_supportsDirectRenderingMethod1 = false;

    public function __construct($name, $description, $flags)
    {
        $this->_name        = $name;
        $this->_description = $description;

        if ($flags & self::VIDEO) {
            $this->_video = true;
        }

        if ($flags & self::AUDIO) {
            $this->_audio = true;
        }

        if ($flags & self::SUBTITLE) {
            $this->_subtitle = true;
        }

        if ($flags & self::FRAME_LEVEL_MULTITHREADING) {
            $this->_frameLevelMultithreading = true;
        }

        if ($flags & self::SLICE_LEVEL_MULTITHREADING) {
            $this->_sliceLevelMultithreading = true;
        }

        if ($flags & self::EXPERIMENTAL) {
            $this->_experimental = true;
        }

        if ($flags & self::SUPPORTS_DRAW_HORIZ_BAND) {
            $this->_supportsDrawHorizBand = true;
        }

        if ($flags & self::SUPPORTS_DIRECT_RENDERING_METHOD_1) {
            $this->_supportsDirectRenderingMethod1 = true;
        }

        $this->addToGetterArray([
            "name"                           => "getName",
            "description"                    => "getDescription",
            "video"                          => "isVideo",
            "audio"                          => "isAudio",
            "subtitle"                       => "isSubtitle",
            "frameLevelMultithreading"       => "isFrameLevelMultithreading",
            "sliceLevelMultithreading"       => "isSliceLevelMultithreading",
            "experimental"                   => "isExperimental",
            "supportsDrawHorizBand"          => "supportsDrawHorizBand",
            "supportsDirectRenderingMethod1" => "supportsDirectRenderingMethod1",
            "flags"                          => "getFlags"
        ]);
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function isVideo()
    {
        return $this->_video;
    }

    public function isAudio()
    {
        return $this->_audio;
    }

    public function isSubtitle()
    {
        return $this->_subtitle;
    }

    public function isFrameLevelMultithreading()
    {
        return $this->_frameLevelMultithreading;
    }

    public function isSliceLevelMultithreading()
    {
        return $this->_sliceLevelMultithreading;
    }

    public function isExperimental()
    {
        return $this->_experimental;
    }

    public function supportsDrawHorizBand()
    {
        return $this->_supportsDrawHorizBand;
    }

    public function supportsDirectRenderingMethod1()
    {
        return $this->_supportsDirectRenderingMethod1;
    }

    public function getFlags()
    {
        $flag = 0;

        if ($this->isVideo()) {
            $flag |= self::VIDEO;
        }

        if ($this->isAudio()) {
            $flag |= self::AUDIO;
        }

        if ($this->isSubtitle()) {
            $flag |= self::SUBTITLE;
        }

        if ($this->isFrameLevelMultithreading()) {
            $flag |= self::FRAME_LEVEL_MULTITHREADING;
        }

        if ($this->isSliceLevelMultithreading()) {
            $flag |= self::SLICE_LEVEL_MULTITHREADING;
        }

        if ($this->isExperimental()) {
            $flag |= self::EXPERIMENTAL;
        }

        if ($this->supportsDrawHorizBand()) {
            $flag |= self::SUPPORTS_DRAW_HORIZ_BAND;
        }

        if ($this->supportsDirectRenderingMethod1()) {
            $flag |= self::SUPPORTS_DIRECT_RENDERING_METHOD_1;
        }

        return $flag;
    }
}
