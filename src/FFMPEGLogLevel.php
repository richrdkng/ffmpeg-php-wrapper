<?php

namespace FFMPEGWrapper;

class FFMPEGLogLevel {

    const QUIET   = -8;

    const PANIC   = 0;

    const FATAL   = 8;

    const ERROR   = 16;

    const WARNING = 24;

    const INFO    = 32;

    const VERBOSE = 40;

    const DEBUG   = 48;

    const TRACE   = 56;

    const DEFAULT_LEVEL = self::INFO;

    private function __construct()
    {
        // noop
    }
}
