<?php

namespace TestCases\GeneralCases;

use FFMPEGWrapper\Exception\FFMPEGNoInputSpecifiedException;
use FFMPEGWrapper\FFMPEG;
use PHPUnit\Framework\TestCase;

class FFMPEGNoInputSpecifiedExceptionTest extends TestCase {

    public function testException()
    {
        $this->expectException(FFMPEGNoInputSpecifiedException::class);

        (new FFMPEG())
            ->run();
    }
}
