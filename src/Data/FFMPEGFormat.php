<?php

namespace FFMPEGWrapper\Data;

class FFMPEGFormat {

    const DEMUXING_SUPPORTED = 0x01;

    const MUXING_SUPPORTED   = 0x02;

    private static $__pattern = "/(?<flags>D?E?)\s+(?<name>[\w,]+)\s+(?<desc>[\w\s()\/\-,'\"]+)/";

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

        $demuxingSupported = strpos($flags, "D") !== FALSE;
        $muxingSupported   = strpos($flags, "E") !== FALSE;

        return new self($name, $desc, $demuxingSupported, $muxingSupported);
    }

    private $_name;

    private $_description;

    private $_demuxingSupported = false;

    private $_muxingSupported = false;

    public function __construct($name, $description, $demuxingSupported, $muxingSupported)
    {
        $this->_name        = $name;
        $this->_description = $description;

        $this->_demuxingSupported = $demuxingSupported;
        $this->_muxingSupported   = $muxingSupported;
    }

    public function isDemuxingSupported()
    {
        return $this->_demuxingSupported;
    }

    public function isMuxingSupported()
    {
        return $this->_muxingSupported;
    }

    public function getFlags()
    {
        $flag = 0;

        if ($this->isDemuxingSupported()) {
            $flag |= self::DEMUXING_SUPPORTED;
        }

        if ($this->isMuxingSupported()) {
            $flag |= self::MUXING_SUPPORTED;
        }

        return $flag;
    }
}
