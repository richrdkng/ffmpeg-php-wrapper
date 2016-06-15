<?php

namespace FFMPEGWrapper\Exception;

class FFMPEGNoCodecSpecifiedException extends \Exception {

    const MESSAGE = "No codec or stream option was specified";
    const CODE    = 19;

    public function __construct($message = self::MESSAGE, $code = self::CODE, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
