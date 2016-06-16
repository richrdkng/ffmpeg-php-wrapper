<?php

namespace TestCases\GeneralCases\Options;

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\OutputOption;
use PHPUnit\Framework\TestCase;

class OutputOptionTest extends TestCase {

    public function testOptionWithDefaultArguments()
    {
        $ffmpeg = (new FFMPEG())
            ->add(
                new OutputOption("output.video")
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -y \"output.video\"", $ffmpeg->getCommandLineArguments());
    }

    public function testOptionWithOverwriteFileFalse()
    {
        $ffmpeg = (new FFMPEG())
            ->add(
                new OutputOption("output.video", false)
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} \"output.video\"", $ffmpeg->getCommandLineArguments());
    }
}
