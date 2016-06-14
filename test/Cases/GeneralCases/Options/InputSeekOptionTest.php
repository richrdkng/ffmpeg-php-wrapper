<?php

namespace TestCases\GeneralCases\Options;

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\InputSeekOption;
use PHPUnit\Framework\TestCase;

class InputSeekOptionTest extends TestCase {

    public function testOption()
    {
        $time   = "02:03:04";
        $ffmpeg = (new FFMPEG())
            ->add(
                new InputSeekOption($time)
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -ss {$time}", $ffmpeg->getShellScript());
    }
}
