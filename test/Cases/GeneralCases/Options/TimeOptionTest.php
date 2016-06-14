<?php

namespace TestCases\GeneralCases\Options;

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\TimeOption;
use PHPUnit\Framework\TestCase;

class TimeOptionTest extends TestCase {

    public function testOption()
    {
        $time   = "01:02:03";
        $ffmpeg = (new FFMPEG())
            ->add(
                new TimeOption($time)
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -t {$time}", $ffmpeg->getShellScript());
    }
}
