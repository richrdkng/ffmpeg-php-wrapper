<?php

namespace TestCases\DefaultCases;

use FFMPEGWrapper\FFMPEG;
use PHPUnit\Framework\TestCase;

class FFMPEGDefaultValuesTest extends TestCase {

    public function testDefaultExecutablePathValue()
    {
        $ffmpeg  = new FFMPEG();

        $this->assertEquals(FFMPEG::DEFAULT_EXECUTABLE_PATH, $ffmpeg->getExecutablePath());
    }

    public function testDefaultCWDValue()
    {
        $ffmpeg  = new FFMPEG();

        $this->assertEquals(FFMPEG::DEFAULT_CWD, $ffmpeg->getCWD());
    }
}
