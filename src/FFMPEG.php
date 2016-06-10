<?php

namespace FFMPEGWrapper;

use FFMPEGWrapper\Data\FFMPEGBuildConfiguration;
use FFMPEGWrapper\Data\FFMPEGCodec;
use FFMPEGWrapper\Data\FFMPEGDecoder;
use FFMPEGWrapper\Data\FFMPEGEncoder;
use FFMPEGWrapper\Data\FFMPEGFormat;
use FFMPEGWrapper\Data\FFMPEGLibrary;
use FFMPEGWrapper\Option\FFMPEGOption;
use FFMPEGWrapper\Option\InputSeekOption;
use FFMPEGWrapper\Option\TimeOption;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FFMPEG {

    const ENVIRONMENT_NIX = 1;
    const ENVIRONMENT_WIN = 2;

    const STATUS_STARTED = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_FINISHED = 3;

    const DEFAULT_PROGRAM_NAME = "ffmpeg";

    public static function runWith($arguments = null)
    {
        $FFMPEG = new self([
            "args" => $arguments
        ]);

        $FFMPEG->run();
    }

    private $_programName = "ffmpeg";

    /** @var string|null */
    private $_version = null;

    /** @var FFMPEGBuildConfiguration[] */
    private $_buildconf = [];

    /** @var FFMPEGLibrary[] */
    private $_libraries = [];

    /** @var FFMPEGFormat[] */
    private $_formats = [];

    /** @var FFMPEGEncoder[] */
    private $_encoders = [];

    /** @var FFMPEGDecoder[] */
    private $_decoders = [];

    /** @var FFMPEGCodec[] */
    private $_codecs = [];

    private $_args;

    private $_isStarted = false;
    private $_inProgress = false;

    private $_description = null;
    private $_conclusion = null;

    private $_totalDuration = 0;
    private $_selectedDuration = 0;

    /** @var callable|null */
    private $_callback = null;

    private $_isStartedFired = false;

    /** @var FFMPEGOption[]  */
    private $_options = [];

    public function __construct(array $args = null)
    {
        $this->_programName = isset($args["programName"]) ?: self::DEFAULT_PROGRAM_NAME;
        $this->_args        = isset($args["args"])        ?: "";

        if (isset($this->_args)) {

        }

        $this->_getFFMPEGData();
    }

    public function add(FFMPEGOption $option, FFMPEGOption ...$options)
    {
        $this->_options[] = $option;

        foreach ($options as $opt) {
            $this->_options[] = $opt;
        }

        return $this;
    }

    public function run(callable $callback = null)
    {
        if ($callback !== null) {
            $this->_callback = $callback;
        }

        $struct = new FFMPEGStatusStruct();
        $commandLine = $this->_getCommandLine();

        $process = new Process($commandLine, OUTPUT . "/video/");
        $process->run(function($type, $buffer) use($struct) {
            static $start = null;

            if ($start === null) {
                $start = microtime(true);
            }

            $struct->totalDuration    = $this->_getTotalDuration();
            $struct->selectedDuration = $this->_getSelectedDuration();
            $struct->currentTime      = 0;
            $struct->currentFrame     = 0;
            $struct->currentPercent   = 0;
            $struct->ETA              = 0;

            if ($this->_isProgressPattern($buffer)) {
                $this->_isStarted = true;
                $this->_inProgress = true;

                $struct->totalDuration    = $this->_getTotalDuration();
                $struct->selectedDuration = $this->_getSelectedDuration();
                $struct->currentTime      = $this->_getTimeFromStatus($buffer);
                $struct->currentFrame     = $this->_getFrameFromStatus($buffer);
                $struct->currentPercent   = $struct->currentTime / $struct->selectedDuration;
                $struct->ETA              = 0;

                if ($struct->currentPercent > 0) {
                    $elapsed = microtime(true) - $start;
                    $struct->ETA  = ($elapsed * (1 / $struct->currentPercent)) - $elapsed;
                }

                if ($this->_isStartedFired) {
                    $struct->isStarted  = false;
                    $struct->isProgress = true;
                    $struct->isEnded    = false;

                    $this->_FireCallback($struct);
                }

            } else {
                $this->_inProgress = false;
            }

            if (! $this->_inProgress()) {
                if (! $this->_isStarted()) {
                    if ($this->_description === null) {
                        $this->_description = "";
                    }

                    $this->_description .= $buffer;
                    $struct->mediaDescription = $this->_description;

                } else {
                    if ($this->_conclusion === null) {
                        $this->_conclusion = "";
                    }

                    $this->_conclusion .= $buffer;
                    $struct->mediaEndStats = $this->_conclusion;
                }
            } else {
                if ($this->_isStarted()) {
                    if (! $this->_isStartedFired) {
                        $this->_isStartedFired = true;

                        $struct->isStarted  = true;
                        $struct->isProgress = false;
                        $struct->isEnded    = false;

                        $this->_FireCallback($struct);
                    }
                }
            }
        });

        $struct->isStarted  = false;
        $struct->isProgress = false;
        $struct->isEnded    = true;

        $this->_FireCallback($struct);
    }

    public function getShellScript()
    {
        return $this->_getCommandLine();
    }

    private function _getFFMPEGData()
    {
        $procForBasicData = new Process("ffmpeg");
        $procForBasicData->start();

        $procForFormatData = new Process("ffmpeg -hide_banner -formats");
        $procForFormatData->start();

        $procForEncoderData = new Process("ffmpeg -hide_banner -encoders");
        $procForEncoderData->start();

        $procForDecoderData = new Process("ffmpeg -hide_banner -decoders");
        $procForDecoderData->start();

        $procForCodecData = new Process("ffmpeg -hide_banner -codecs");
        $procForCodecData->start();

        $this->_processBasicData($procForBasicData);
        $this->_processFormatData($procForFormatData);
        $this->_processEncoderData($procForEncoderData);
        $this->_processDecoderData($procForDecoderData);
        $this->_processCodecData($procForCodecData);
    }

    private function _processBasicData(Process $process)
    {
        $process->wait();

        $output = strlen($process->getOutput()) > 0 ? $process->getOutput() : $process->getErrorOutput();

        $outputWithoutNewline = str_replace("\n", "", $output);

        // parse version
            $versionPattern = "/^.*?ffmpeg.*?version.*?(?<version>[\d\.\-\w]+)/";

            if (preg_match($versionPattern, $output, $matches)) {
                $this->_version = $matches["version"];
            }

            if ($this->_version === null) {
                throw new \Exception("FFMPEG version not found in: \"{$outputWithoutNewline}\"");
            }

        // parse build configurations
            $buildconfPattern = "/(?<conf>--[\w\=\-]+)/";

            if (preg_match_all($buildconfPattern, $output, $matches)) {
                if (isset($matches["conf"])) {
                    foreach ($matches["conf"] as $conf) {
                        $this->_buildconf[] = new FFMPEGBuildConfiguration($conf);
                    }
                }
            }

            if (count($this->_buildconf) === 0) {
                throw new \Exception("FFMPEG buildconf not found in: \"{$outputWithoutNewline}\"");
            }

        // parse libraries
            $libPattern = "/(?<lib_name>lib\w+)\s+(?<lib_ver>[\d\.\s]+)/";

            if (preg_match_all($libPattern, $output, $matches)) {
                foreach (array_combine($matches["lib_name"], $matches["lib_ver"]) as $name => $version) {
                    $this->_libraries[] = new FFMPEGLibrary($name, $version);
                }
            }

            if (count($this->_libraries) === 0) {
                throw new \Exception("FFMPEG libraries not found in: \"{$outputWithoutNewline}\"");
            }
    }

    private function _processFormatData(Process $process)
    {
        $process->wait();

        $output = strlen($process->getOutput()) > 0 ? $process->getOutput() : $process->getErrorOutput();

        $outputWithoutNewline = str_replace("\n", "", $output);

        $clearPattern  = "/--(?<formats>.*)/s";
        $formatPattern = "/(?<format>[\w].*)[\n\r\f]*/";

        if (preg_match($clearPattern, $output, $matches)) {
            $formats = $matches["formats"];

            if (preg_match_all($formatPattern, $formats, $matches)) {
                foreach ($matches["format"] as $format) {
                    if (FFMPEGFormat::match($format)) {
                        $this->_formats[] = FFMPEGFormat::from($format);
                    }
                }
            }
        }

        if (count($this->_formats) === 0) {
            throw new \Exception("FFMPEG formats not found in: \"{$outputWithoutNewline}\"");
        }
    }

    private function _processEncoderData(Process $process)
    {
        $process->wait();

        $output = strlen($process->getOutput()) > 0 ? $process->getOutput() : $process->getErrorOutput();

        $outputWithoutNewline = str_replace("\n", "", $output);

        $clearPattern   = "/------(?<encoders>.*)/s";
        $encoderPattern = "/(?<encoder>[\w\.].*)[\n\r\f]*/";

        if (preg_match($clearPattern, $output, $matches)) {
            $encoders = $matches["encoders"];

            if (preg_match_all($encoderPattern, $encoders, $matches)) {
                foreach ($matches["encoder"] as $encoder) {
                    if (FFMPEGEncoder::match($encoder)) {
                        $this->_encoders[] = FFMPEGEncoder::from($encoder);
                    }
                }
            }
        }

        if (count($this->_encoders) === 0) {
            throw new \Exception("FFMPEG encoders not found in: \"{$outputWithoutNewline}\"");
        }
    }

    private function _processDecoderData(Process $process)
    {
        $process->wait();

        $output = strlen($process->getOutput()) > 0 ? $process->getOutput() : $process->getErrorOutput();

        $outputWithoutNewline = str_replace("\n", "", $output);

        $clearPattern   = "/------(?<decoders>.*)/s";
        $decoderPattern = "/(?<decoder>[\w\.].*)[\n\r\f]*/";

        if (preg_match($clearPattern, $output, $matches)) {
            $decoders = $matches["decoders"];

            if (preg_match_all($decoderPattern, $decoders, $matches)) {
                foreach ($matches["decoder"] as $decoder) {
                    if (FFMPEGDecoder::match($decoder)) {
                        $this->_decoders[] = FFMPEGDecoder::from($decoder);
                    }
                }
            }
        }

        if (count($this->_decoders) === 0) {
            throw new \Exception("FFMPEG decoders not found in: \"{$outputWithoutNewline}\"");
        }
    }

    private function _processCodecData(Process $process)
    {
        $process->wait();

        $output = strlen($process->getOutput()) > 0 ? $process->getOutput() : $process->getErrorOutput();

        $outputWithoutNewline = str_replace("\n", "", $output);

        $clearPattern = "/-------(?<codecs>.*)/s";
        $codecPattern = "/(?<codec>[\w\.].*)[\n\r\f]*/";

        if (preg_match($clearPattern, $output, $matches)) {
            $codecs = $matches["codecs"];

            if (preg_match_all($codecPattern, $codecs, $matches)) {
                foreach ($matches["codec"] as $codec) {
                    if (FFMPEGCodec::match($codec)) {
                        $this->_codecs[] = FFMPEGCodec::from($codec);
                    }
                }
            }
        }

        if (count($this->_codecs) === 0) {
            throw new \Exception("FFMPEG codecs not found in: \"{$outputWithoutNewline}\"");
        }
    }

    private function _isStarted()
    {
        return $this->_isStarted;
    }

    private function _inProgress()
    {
        return $this->_inProgress;
    }

    private function _getCommandLine()
    {
        $executable  = $this->_programName;
        $args        = $this->_compileArgs();

        return "${executable} ${args}";
    }

    private function _compileArgs()
    {
        $args = "";

        foreach ($this->_options as $opt) {
            $args .= $opt->toFFMPEGArgOption() . " ";
        }

        $args = mb_substr($args, 0, mb_strlen($args, "UTF-8") - 1, "UTF-8");

        return $args;
    }

    private function _stod($string)
    {
        $pattern = "/(?<H>\d{2}):(?<i>\d{2}):(?<s>\d{2})(?<u>\.\d+)?/";

        if (preg_match($pattern, $string, $matches)) {
            $hours   = intval($matches['H']);
            $minutes = intval($matches['i']);
            $seconds = intval($matches['s']);
            $micros  = isset($matches['u']) ? floatval($matches['u']) : 0;

            return $hours * 3600 +
                   $minutes * 60 +
                   $seconds +
                   $micros;
        }

        return 0;
    }

    private function _getTotalDuration()
    {
        if ($this->_totalDuration === 0) {
            if ($this->_description !== null) {
                $durationPattern = "/.*?Duration:.*?(?<duration>[\d\:\.]+).*?start:.*?bitrate:/";

                if (preg_match($durationPattern, $this->_description, $matches)) {
                    $this->_totalDuration = $this->_stod($matches['duration']);
                }
            }
        }

        return $this->_totalDuration;
    }

    private function _getSelectedDuration()
    {
        if ($this->_selectedDuration === 0) {
            $totalDuration = $this->_getTotalDuration();

            if ($totalDuration > 0) {
                $timeDuration = $totalDuration;

                foreach ($this->_options as $opt) {
                    if ($opt instanceof TimeOption) {
                        $timeDuration = $this->_stod($opt->getTime());
                    }
                }

                $this->_selectedDuration = $timeDuration;
            }
        }

        return $this->_selectedDuration;
    }

    private function _getFrameFromStatus($statusString)
    {
        $framePattern = "/\s*frame=\s*(?<frame>\d+)/";

        preg_match($framePattern, $statusString, $matches);

        return isset($matches["frame"]) ? intval($matches["frame"]) : 0;
    }

    private function _getTimeFromStatus($statusString)
    {
        $timePattern = "/\s*time=\s*(?<time>[\d\:\.]+)/";

        preg_match($timePattern, $statusString, $matches);

        return isset($matches["time"]) ? $this->_stod($matches["time"]) : 0;
    }

    private function _isProgressPattern($buffer)
    {
        $pattern = "/^.*frame=.*fps=.*time=.*speed=.*$/";

        if (preg_match($pattern, $buffer)) {
            return true;
        }

        return false;
    }

    private function _FireCallback(FFMPEGStatusStruct $struct)
    {
        if ($this->_callback !== null) {
            $this->_callback->__invoke(FFMPEGStatus::from($struct));
        }
    }
}
