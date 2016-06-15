<?php

namespace FFMPEGWrapper\Exception;

class FFMPEGNoOptionPassedException extends \Exception {

    const MESSAGE = "No FFMPEGOption was passed";
    const CODE    = 21;

    public function __construct($message = self::MESSAGE, $code = self::CODE, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
