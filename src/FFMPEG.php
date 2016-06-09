<?php

namespace FFMPEGWrapper;

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

        $progressPattern = "/^.*frame=.*fps=.*time=.*speed=.*$/";
        $framePattern = "/\s*frame=\s*(\d+)/";
        $timePattern = "/\s*time=\s*(?<time>[\d\:\.]+)/";

        //$commandLine = $this->_programName . " " . $this->_args;

        $executable  = $this->_programName;
        $args        = $this->_compileArgs();
        $commandLine = "${executable} ${args}";

        /*
        return;

        $input = SAMPLES . "/video/big_buck_bunny_1080p_h264.mov";
        $output = "/vagrant/tests/big_buck_bunny_1080p_h264.mp4";

        $commandLine = "ffmpeg -ss 00:00:30 -i ${input} -t 00:00:05 -y ${output}";
        */

        $file_name = "big_buck_bunny_1080p_h264";
        $file_format = "mp4";
        $input_file = "${file_name}.mov";
        $output_file = "${file_name}.${file_format}";
        $force_format = $file_format;

        $input = SAMPLES . "/video/${input_file}";
        $output = OUTPUT . "/video/${output_file}";
        $passlog = "${output_file}";

        //$commandLine = "ffmpeg -ss 00:00:30 -i ${input} -t 00:00:05 -y ${output}";
        //$commandLine = "ffmpeg -ss 00:00:30 -i \"${input}\" -t 00:00:05 -pass 1 -passlogfile \"${passlog}\" -an -f ${force_format} -y /dev/null";
        $commandLine = $this->_getCommandLine();

        $struct = new FFMPEGStatusStruct();

        $process = new Process($commandLine, OUTPUT . "/video/");
        $process->run(function($type, $buffer) use($struct) {
            static $start = null;

            if ($start === null) {
                $start = microtime(true);
            }

            // var_dump($buffer);

            //echo "|" . $buffer . "|";

            /*
            /if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo 'OUT > '.$buffer;
            }
            */

            $struct->totalDuration    = $this->_getTotalDuration();
            $struct->selectedDuration = $this->_getSelectedDuration();
            $struct->currentTime      = 0;
            $struct->currentFrame     = 0;
            $struct->currentPercent   = 0;
            $struct->ETA              = 0;

            /*
            $totalDuration    = $this->_getTotalDuration();
            $selectedDuration = $this->_getSelectedDuration();
            $currentTime      = 0;
            $currentFrame     = 0;
            $currentPercent   = 0;
            $ETA              = 0;
            */

            if ($this->_isProgressPattern($buffer)) {
                $this->_isStarted = true;
                $this->_inProgress = true;

                // echo "match:: " . $buffer;

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

                /*
                $totalDuration    = $this->_getTotalDuration();
                $selectedDuration = $this->_getSelectedDuration();
                $currentTime      = $this->_getTimeFromStatus($buffer);
                $currentFrame     = $this->_getFrameFromStatus($buffer);
                $currentPercent   = $currentTime / $selectedDuration;

                if ($currentPercent > 0) {
                    $elapsed = microtime(true) - $start;
                    //$ETA = $elapsed * (1 / $currentPercent);
                    $ETA = ($elapsed * (1 / $currentPercent)) - $elapsed;
                }

                print "{$totalDuration} / {$selectedDuration} --- {$currentFrame} - {$currentTime} --- {$currentPercent} <<< {$ETA} >>>\n";
                */

                if ($this->_isStartedFired) {
                    $struct->isStarted  = false;
                    $struct->isProgress = true;
                    $struct->isEnded    = false;

                    $this->_FireCallback($struct);
                }

            } else {
                $this->_inProgress = false;

                //echo "[[[" . $buffer . "]]]";
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

        /*
        echo "=======================================================================================================";
        echo "\n{$this->_description}\n";
        echo "=======================================================================================================";

        echo "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||";
        echo "\n{$this->_conclusion}\n";
        echo "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||";
        */

        echo "\nEND\n";
    }

    public function getShellScript()
    {
        return $this->_getCommandLine();
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
