<?php

namespace FFMPEGWrapper\Exception;

class FFMPEGNoSuchFileOrDirectoryException extends \Exception {

    const MESSAGE = "No such file or directory: \"%s\"";
    const CODE    = 11;

    public function __construct($path, $code = self::CODE, \Exception $previous = null)
    {
        $message = sprintf(self::MESSAGE, $path);

        parent::__construct($message, $code, $previous);
    }
}
