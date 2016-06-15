<?php

namespace FFMPEGWrapper\Exception;

class FFMPEGNoOutputSpecifiedException extends \Exception {

    const MESSAGE = "At least one output file must be specified";
    const CODE    = 13;

    public function __construct($message = self::MESSAGE, $code = self::CODE, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
