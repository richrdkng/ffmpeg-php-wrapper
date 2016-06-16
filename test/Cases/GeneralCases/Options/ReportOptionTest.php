<?php

namespace TestCases\GeneralCases\Options;

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\FFMPEGLogLevel;
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

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -report", $ffmpeg->getCommandLineArguments());
    }

    public function testOptionWithFileSpecified()
    {
        $file   = "report-file";
        $ffmpeg = (new FFMPEG())
            ->add(
                new ReportOption($file)
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -report", $ffmpeg->getCommandLineArguments());
        $this->assertArraySubset(
            [
                "FFREPORT" => "file={$file}"
            ],
            $ffmpeg->getEnvironmentVariables()
        );
    }

    public function testOptionWithFileAndLogLevelSpecified()
    {
        $file     = "report.log";
        $logLevel = FFMPEGLogLevel::WARNING;
        $ffmpeg   = (new FFMPEG())
            ->add(
                new ReportOption($file, $logLevel)
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -report", $ffmpeg->getCommandLineArguments());
        $this->assertArraySubset(
            [
                "FFREPORT" => "file={$file}:level={$logLevel}"
            ],
            $ffmpeg->getEnvironmentVariables()
        );
    }
}
