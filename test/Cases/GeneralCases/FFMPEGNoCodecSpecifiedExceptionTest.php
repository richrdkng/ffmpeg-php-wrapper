<?php

namespace TestCases\GeneralCases;

use FFMPEGWrapper\Exception\FFMPEGNoCodecSpecifiedException;
use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\InputOption;
use FFMPEGWrapper\Option\OutputOption;
use PHPUnit\Framework\TestCase;

class FFMPEGNoCodecSpecifiedExceptionTest extends TestCase {

    public function testException()
    {
        $this->expectException(FFMPEGNoCodecSpecifiedException::class);

        (new FFMPEG())
            ->add(
                new InputOption(DUMMIES_DIR . "/dummy.video"),
                new OutputOption(OUTPUT_DIR . "/dummy.video")
            )
            ->run();
    }
}
