<?php

namespace TestCases\GeneralCases;

use FFMPEGWrapper\Exception\FFMPEGNoOptionPassedException;
use FFMPEGWrapper\FFMPEG;
use PHPUnit\Framework\TestCase;

class FFMPEGNoOptionPassedExceptionTest extends TestCase {

    public function testException()
    {
        $this->expectException(FFMPEGNoOptionPassedException::class);

        (new FFMPEG())
            ->add()
            ->run();
    }
}
