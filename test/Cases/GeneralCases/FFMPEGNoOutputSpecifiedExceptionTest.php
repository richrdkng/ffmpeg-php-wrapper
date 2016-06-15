<?php

namespace TestCases\GeneralCases;

use FFMPEGWrapper\Exception\FFMPEGNoOutputSpecifiedException;
use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\InputOption;
use PHPUnit\Framework\TestCase;

class FFMPEGNoOutputSpecifiedExceptionTest extends TestCase {

    public function testException()
    {
        $this->expectException(FFMPEGNoOutputSpecifiedException::class);

        (new FFMPEG())
            ->add(new InputOption(DUMMIES_DIR . "/dummy.video"))
            ->run();
    }
}
