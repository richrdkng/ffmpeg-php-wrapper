<?php

namespace FFMPEGWrapper\Data;

class FFMPEGEncoder extends FFMPEGEncoderDecoder {

    public static function from($string)
    {
        $encoderDecoder = parent::from($string);

        return new self(
            $encoderDecoder->getName(),
            $encoderDecoder->getDescription(),
            $encoderDecoder->getFlags()
        );
    }

    public function __construct($name, $description, $flags)
    {
        parent::__construct($name, $description, $flags);
    }
}
