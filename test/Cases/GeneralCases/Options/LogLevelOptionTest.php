<?php

namespace TestCases\GeneralCases\Options;

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\FFMPEGLogLevel;
use FFMPEGWrapper\Option\LogLevelOption;
use PHPUnit\Framework\TestCase;

class LogLevelOptionTest extends TestCase
{
    public function testOptionWithDefaultArguments()
    {
        $defaultLevel = FFMPEGLogLevel::DEFAULT_LEVEL;

        $ffmpeg = (new FFMPEG())
            ->add(
                new LogLevelOption()
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -loglevel {$defaultLevel}", $ffmpeg->getCommandLineArguments());
    }

    public function testOptionWithAllOptions()
    {
        $quietLevel   = FFMPEGLogLevel::QUIET;
        $panicLevel   = FFMPEGLogLevel::PANIC;
        $fatalLevel   = FFMPEGLogLevel::FATAL;
        $errorLevel   = FFMPEGLogLevel::ERROR;
        $warningLevel = FFMPEGLogLevel::WARNING;
        $infoLevel    = FFMPEGLogLevel::INFO;
        $verboseLevel = FFMPEGLogLevel::VERBOSE;
        $debugLevel   = FFMPEGLogLevel::DEBUG;
        $traceLevel   = FFMPEGLogLevel::TRACE;
        $defaultLevel = FFMPEGLogLevel::DEFAULT_LEVEL;

        $quietFFMPEG   = (new FFMPEG())->add(new LogLevelOption($quietLevel));
        $panicFFMPEG   = (new FFMPEG())->add(new LogLevelOption($panicLevel));
        $fatalFFMPEG   = (new FFMPEG())->add(new LogLevelOption($fatalLevel));
        $errorFFMPEG   = (new FFMPEG())->add(new LogLevelOption($errorLevel));
        $warningFFMPEG = (new FFMPEG())->add(new LogLevelOption($warningLevel));
        $infoFFMPEG    = (new FFMPEG())->add(new LogLevelOption($infoLevel));
        $verboseFFMPEG = (new FFMPEG())->add(new LogLevelOption($verboseLevel));
        $debugFFMPEG   = (new FFMPEG())->add(new LogLevelOption($debugLevel));
        $traceFFMPEG   = (new FFMPEG())->add(new LogLevelOption($traceLevel));
        $defaultFFMPEG = (new FFMPEG())->add(new LogLevelOption($defaultLevel));

        $this->assertEquals(
            "{$quietFFMPEG->getExecutablePath()} -loglevel {$quietLevel}",
            $quietFFMPEG->getCommandLineArguments()
        );

        $this->assertEquals(
            "{$panicFFMPEG->getExecutablePath()} -loglevel {$panicLevel}",
            $panicFFMPEG->getCommandLineArguments()
        );

        $this->assertEquals(
            "{$fatalFFMPEG->getExecutablePath()} -loglevel {$fatalLevel}",
            $fatalFFMPEG->getCommandLineArguments()
        );

        $this->assertEquals(
            "{$errorFFMPEG->getExecutablePath()} -loglevel {$errorLevel}",
            $errorFFMPEG->getCommandLineArguments()
        );

        $this->assertEquals(
            "{$warningFFMPEG->getExecutablePath()} -loglevel {$warningLevel}",
            $warningFFMPEG->getCommandLineArguments()
        );

        $this->assertEquals(
            "{$infoFFMPEG->getExecutablePath()} -loglevel {$infoLevel}",
            $infoFFMPEG->getCommandLineArguments()
        );

        $this->assertEquals(
            "{$verboseFFMPEG->getExecutablePath()} -loglevel {$verboseLevel}",
            $verboseFFMPEG->getCommandLineArguments()
        );

        $this->assertEquals(
            "{$debugFFMPEG->getExecutablePath()} -loglevel {$debugLevel}",
            $debugFFMPEG->getCommandLineArguments()
        );

        $this->assertEquals(
            "{$traceFFMPEG->getExecutablePath()} -loglevel {$traceLevel}",
            $traceFFMPEG->getCommandLineArguments()
        );

        $this->assertEquals(
            "{$defaultFFMPEG->getExecutablePath()} -loglevel {$defaultLevel}",
            $defaultFFMPEG->getCommandLineArguments()
        );
    }
}
