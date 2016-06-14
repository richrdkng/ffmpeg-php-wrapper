<?php

namespace TestCases\GeneralCases\Options;

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\PassOption;
use PHPUnit\Framework\TestCase;

class PassOptionTest extends TestCase {

    public function testOptionWithDefaultArguments()
    {
        // 1st pass
        $ffmpeg = (new FFMPEG())
            ->add(
                new PassOption(1)
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -pass 1 -an -y /dev/null", $ffmpeg->getShellScript());

        // 2nd or more pass
        $ffmpeg = (new FFMPEG())
            ->add(
                new PassOption(2)
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -pass 2", $ffmpeg->getShellScript());
    }

    public function testOptionWithForcedFormat()
    {
        $format = "mp4";
        $ffmpeg = (new FFMPEG())
            ->add(
                new PassOption(1, $format)
            );

        $this->assertEquals(
            "{$ffmpeg->getExecutablePath()} -pass 1 -f {$format} -an -y /dev/null",
            $ffmpeg->getShellScript()
        );
    }

    public function testOptionWithCustomLogFile()
    {
        $custom = "custom-logfile";
        $ffmpeg = (new FFMPEG())
            ->add(
                new PassOption(1, null, $custom)
            );

        $this->assertEquals(
            "{$ffmpeg->getExecutablePath()} -pass 1 -passlogfile \"{$custom}\" -an -y /dev/null",
            $ffmpeg->getShellScript()
        );
    }
    public function testOptionWithForcedFormatAndCustomLogFile()
    {
        $format = "mp4";
        $custom = "custom-logfile";
        $ffmpeg = (new FFMPEG())
            ->add(
                new PassOption(1, $format, $custom)
            );

        $this->assertEquals(
            "{$ffmpeg->getExecutablePath()} -pass 1 -f {$format} -passlogfile \"{$custom}\" -an -y /dev/null",
            $ffmpeg->getShellScript()
        );
    }
}
