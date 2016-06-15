<?php

namespace TestCases\GeneralCases\Options;

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\ReportOption;
use PHPUnit\Framework\TestCase;

class ReportOptionTest extends TestCase
{
    public function testOptionWithDefaultArguments()
    {
        $ffmpeg = (new FFMPEG())
            ->add(
                new ReportOption()
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -report", $ffmpeg->getShellScript());
    }

    public function testOptionWithFileSpecified()
    {
        $file   = "report-file";
        $ffmpeg = (new FFMPEG())
            ->add(
                new ReportOption($file)
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -report \"{$file}\"", $ffmpeg->getShellScript());
    }
}
