<?php

namespace TestCases\GeneralCases;

use FFMPEGWrapper\Exception\FFMPEGNoSuchFileOrDirectoryException;
use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\InputOption;
use PHPUnit\Framework\TestCase;

class FFMPEGNoSuchFileOrDirectoryExceptionTest extends TestCase {

    public function testException()
    {
        $this->expectException(FFMPEGNoSuchFileOrDirectoryException::class);

        (new FFMPEG())
            ->add(new InputOption(uniqid()))
            ->run();
    }
}
