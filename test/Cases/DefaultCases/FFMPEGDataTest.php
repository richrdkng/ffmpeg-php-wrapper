<?php

namespace TestCases\DefaultCases;

use FFMPEGWrapper\FFMPEG;
use PHPUnit\Framework\TestCase;

class FFMPEGDataTest extends TestCase {

    public function testVersionData()
    {
        $ffmpeg  = new FFMPEG();
        $pattern = "/^[\w\.\-]+$/i";

        $this->assertEquals(true, preg_match($pattern, $ffmpeg->getVersion()));
    }

    public function testBuildConfigurationData()
    {
        $ffmpeg = new FFMPEG();

        $this->assertEquals(true, $ffmpeg->hasBuildConfigurations());
    }

    public function testLibrariesData()
    {
        $ffmpeg = new FFMPEG();

        $this->assertEquals(true, $ffmpeg->hasLibraries());
    }

    public function testFormatsData()
    {
        $ffmpeg = new FFMPEG();

        $this->assertEquals(true, $ffmpeg->hasFormats());
    }

    public function testEncodersData()
    {
        $ffmpeg = new FFMPEG();

        $this->assertEquals(true, $ffmpeg->hasEncoders());
    }

    public function testDecodersData()
    {
        $ffmpeg = new FFMPEG();

        $this->assertEquals(true, $ffmpeg->hasDecoders());
    }

    public function testCodecData()
    {
        $ffmpeg = new FFMPEG();

        $this->assertEquals(true, $ffmpeg->hasCodecs());
    }
}
