<?php

namespace TestCases\GeneralCases;

use FFMPEGWrapper\Exception\FFMPEGNotFoundException;
use FFMPEGWrapper\FFMPEG;
use PHPUnit\Framework\TestCase;

class FFMPEGNotFoundExceptionTest extends TestCase {

    public function testException()
    {
        $this->expectException(FFMPEGNotFoundException::class);

        new FFMPEG("ffmpeg" . uniqid());
    }
}
