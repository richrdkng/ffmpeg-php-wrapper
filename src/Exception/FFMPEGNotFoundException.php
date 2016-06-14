<?php

namespace FFMPEGWrapper\Exception;

class FFMPEGNotFoundException extends \Exception {

    const MESSAGE = "FFMPEG executable not found on: \"%s\"";
    const CODE    = 127;

    public function __construct($executablePath, $code = self::CODE, \Exception $previous = null)
    {
        $message = sprintf(self::MESSAGE, $executablePath);

        parent::__construct($message, $code, $previous);
    }
}
