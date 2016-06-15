<?php

namespace FFMPEGWrapper\Exception;

class FFMPEGNoInputSpecifiedException extends \Exception {

    const MESSAGE = "No input file was specified";
    const CODE    = 15;

    public function __construct($message = self::MESSAGE, $code = self::CODE, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
