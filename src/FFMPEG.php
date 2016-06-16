<?php

namespace FFMPEGWrapper;

use FFMPEGWrapper\Data\FFMPEGBuildConfiguration;
use FFMPEGWrapper\Data\FFMPEGCodec;
use FFMPEGWrapper\Data\FFMPEGDataFilter;
use FFMPEGWrapper\Data\FFMPEGDecoder;
use FFMPEGWrapper\Data\FFMPEGEncoder;
use FFMPEGWrapper\Data\FFMPEGFormat;
use FFMPEGWrapper\Data\FFMPEGLibrary;
use FFMPEGWrapper\Exception\FFMPEGNoCodecSpecifiedException;
use FFMPEGWrapper\Exception\FFMPEGNoInputSpecifiedException;
use FFMPEGWrapper\Exception\FFMPEGNoOptionPassedException;
use FFMPEGWrapper\Exception\FFMPEGNoOutputSpecifiedException;
use FFMPEGWrapper\Exception\FFMPEGNotFoundException;
use FFMPEGWrapper\Option\CodecOption;
use FFMPEGWrapper\Option\FFMPEGOption;
use FFMPEGWrapper\Option\InputOption;
use FFMPEGWrapper\Option\OutputOption;
use FFMPEGWrapper\Option\TimeOption;
use FFMPEGWrapper\Shell\ShellAdapter;
use FFMPEGWrapper\Status\FFMPEGStatus;
use FFMPEGWrapper\Status\FFMPEGStatusStruct;
use Symfony\Component\Process\Process;
use function FFMPEGWrapper\Common\getProperty;

class FFMPEG {

    const DEFAULT_EXECUTABLE_PATH = "ffmpeg";

    const DEFAULT_CWD = null;

    public static function runWith()
    {
        $FFMPEG = new self();
        $FFMPEG->run();
    }

    private $_executablePath = self::DEFAULT_EXECUTABLE_PATH;

    /** @var string|null */
    private $_version = null;

    /** @var FFMPEGDataFilter */
    private $_buildconf = null;

    /** @var FFMPEGDataFilter */
    private $_libraries = null;

    /** @var FFMPEGDataFilter */
    private $_formats = null;

    /** @var FFMPEGDataFilter */
    private $_encoders = null;

    /** @var FFMPEGDataFilter */
    private $_decoders = null;

    /** @var FFMPEGDataFilter */
    private $_codecs = null;

    private $_isStarted = false;
    private $_inProgress = false;

    private $_description = null;
    private $_conclusion = null;

    private $_totalDuration = 0;
    private $_selectedDuration = 0;

    private $_isStartedFired = false;

    /** @var callable|null */
    private $_callback = null;

    /** @var FFMPEGOption[]  */
    private $_options = [];

    private $_cwd = null;

    public function __construct($executablePath = self::DEFAULT_EXECUTABLE_PATH, array $args = null)
    {
        $this->_executablePath = $executablePath;
        $this->_cwd            = getProperty($args, "[cwd]", self::DEFAULT_CWD);

        $this->_getFFMPEGData();
    }

    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * @param array|null $filters
     *
     * @return Data\FFMPEGBuildConfiguration[]
     */
    public function getBuildConfigurations(array $filters = null)
    {
        return $this->_buildconf->getFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return int
     */
    public function numBuildConfigurations(array $filters = null)
    {
        return $this->_buildconf->numFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return bool
     */
    public function hasBuildConfigurations(array $filters = null)
    {
        return $this->_buildconf->hasFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return Data\FFMPEGLibrary[]
     */
    public function getLibraries(array $filters = null)
    {
        return $this->_libraries->getFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return int
     */
    public function numLibraries(array $filters = null)
    {
        return $this->_libraries->numFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return bool
     */
    public function hasLibraries(array $filters = null)
    {
        return $this->_libraries->hasFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return Data\FFMPEGFormat[]
     */
    public function getFormats(array $filters = null)
    {
        return $this->_formats->getFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return int
     */
    public function numFormats(array $filters = null)
    {
        return $this->_formats->numFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return bool
     */
    public function hasFormats(array $filters = null)
    {
        return $this->_formats->hasFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return Data\FFMPEGEncoder[]
     */
    public function getEncoders(array $filters = null)
    {
        return $this->_encoders->getFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return int
     */
    public function numEncoders(array $filters = null)
    {
        return $this->_encoders->numFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return bool
     */
    public function hasEncoders(array $filters = null)
    {
        return $this->_encoders->hasFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return Data\FFMPEGDecoder[]
     */
    public function getDecoders(array $filters = null)
    {
        return $this->_decoders->getFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return int
     */
    public function numDecoders(array $filters = null)
    {
        return $this->_decoders->numFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return bool
     */
    public function hasDecoders(array $filters = null)
    {
        return $this->_decoders->hasFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return Data\FFMPEGCodec[]
     */
    public function getCodecs(array $filters = null)
    {
        return $this->_codecs->getFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return int
     */
    public function numCodecs(array $filters = null)
    {
        return $this->_codecs->numFilteredData($filters);
    }

    /**
     * @param array|null $filters
     *
     * @return bool
     */
    public function hasCodecs(array $filters = null)
    {
        return $this->_codecs->hasFilteredData($filters);
    }

    public function getExecutablePath()
    {
        return $this->_executablePath;
    }

    public function getCWD()
    {
        return $this->_cwd;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function add(FFMPEGOption ...$options)
    {
        if (count($options) === 0) {
            throw new FFMPEGNoOptionPassedException();
        }

        foreach ($options as $option) {
            $this->_options[] = $option;
        }

        return $this;
    }

    public function run(callable $callback = null, array $args = null)
    {
        $this->_checkOptions();

        if ($callback !== null) {
            $this->_callback = $callback;
        }

        $process = new Process(
            $this->_getCommandLine(),
            getProperty($args, "[cwd]", $this->getCWD()),
            $this->_compileEnvVars()
        );
        $struct = new FFMPEGStatusStruct();

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

    public function getCommandLineArguments()
    {
        return $this->_getCommandLine();
    }

    public function getEnvironmentVariables()
    {
        return $this->_compileEnvVars(true);
    }

    public function getShellCommand(ShellAdapter $adapter = null)
    {
        if ($adapter === null) {
            return $this->getCommandLineArguments();
        }

        return $adapter->toShellCommand($this);
    }

    private function _getFFMPEGData()
    {
        $executable = $this->getExecutablePath();

        $procForBasicData = new Process($executable);
        $procForBasicData->start();

        $procForFormatData = new Process("{$executable} -hide_banner -formats");
        $procForFormatData->start();

        $procForEncoderData = new Process("{$executable} -hide_banner -encoders");
        $procForEncoderData->start();

        $procForDecoderData = new Process("{$executable} -hide_banner -decoders");
        $procForDecoderData->start();

        $procForCodecData = new Process("{$executable} -hide_banner -codecs");
        $procForCodecData->start();

        $this->_processBasicData($procForBasicData);
        $this->_processFormatData($procForFormatData);
        $this->_processEncoderData($procForEncoderData);
        $this->_processDecoderData($procForDecoderData);
        $this->_processCodecData($procForCodecData);
    }

    private function _processBasicData(Process $process)
    {
        $exitcode = $process->wait();

        if ($exitcode === FFMPEGNotFoundException::CODE) {
            throw new FFMPEGNotFoundException($this->getExecutablePath());
        }

        $output = $this->_getProcessOutput($process);

        // parse version
            $versionPattern = "/^.*?ffmpeg.*?version.*?(?<version>[\d\.\-\w]+)/";

            if (preg_match($versionPattern, $output, $matches)) {
                $this->_version = $matches["version"];
            }

            if ($this->_version === null) {
                throw new \Exception("FFMPEG version not found in: \"{$this->_getProcessOutput($process, true)}\"");
            }

        // parse build configurations
            $this->_buildconf = new FFMPEGDataFilter();
            $buildconfPattern = "/(?<conf>--[\w\=\-\/]+)/";

            if (preg_match_all($buildconfPattern, $output, $matches)) {
                if (isset($matches["conf"])) {
                    foreach ($matches["conf"] as $conf) {
                        $this->_buildconf->addData(new FFMPEGBuildConfiguration($conf));
                    }
                }
            }

            if (! $this->_buildconf->hasData()) {
                throw new \Exception("FFMPEG buildconf not found in: \"{$this->_getProcessOutput($process, true)}\"");
            }

        // parse libraries
            $this->_libraries = new FFMPEGDataFilter();
            $libPattern = "/(?<lib_name>lib\w+)\s+(?<lib_ver>[\d\.\s]+)/";

            if (preg_match_all($libPattern, $output, $matches)) {
                foreach (array_combine($matches["lib_name"], $matches["lib_ver"]) as $name => $version) {
                    $this->_libraries->addData(new FFMPEGLibrary($name, $version));
                }
            }

            if (! $this->_libraries->hasData()) {
                throw new \Exception("FFMPEG libraries not found in: \"{$this->_getProcessOutput($process, true)}\"");
            }
    }

    private function _processFormatData(Process $process)
    {
        $process->wait();

        $this->_formats = new FFMPEGDataFilter();
        $clearPattern   = "/--(?<formats>.*)/s";
        $formatPattern  = "/(?<format>[\w].*)[\n\r\f]*/";
        $output         = $this->_getProcessOutput($process);

        if (preg_match($clearPattern, $output, $matches)) {
            $formats = $matches["formats"];

            if (preg_match_all($formatPattern, $formats, $matches)) {
                foreach ($matches["format"] as $format) {
                    if (FFMPEGFormat::match($format)) {
                        $this->_formats->addData(FFMPEGFormat::from($format));
                    }
                }
            }
        }

        if (! $this->_formats->hasData()) {
            throw new \Exception("FFMPEG formats not found in: \"{$this->_getProcessOutput($process, true)}\"");
        }
    }

    private function _processEncoderData(Process $process)
    {
        $process->wait();

        $this->_encoders = new FFMPEGDataFilter();
        $clearPattern    = "/------(?<encoders>.*)/s";
        $encoderPattern  = "/(?<encoder>[\w\.].*)[\n\r\f]*/";
        $output          = $this->_getProcessOutput($process);

        if (preg_match($clearPattern, $output, $matches)) {
            $encoders = $matches["encoders"];

            if (preg_match_all($encoderPattern, $encoders, $matches)) {
                foreach ($matches["encoder"] as $encoder) {
                    if (FFMPEGEncoder::match($encoder)) {
                        $this->_encoders->addData(FFMPEGEncoder::from($encoder));
                    }
                }
            }
        }

        if (! $this->_encoders->hasData()) {
            throw new \Exception("FFMPEG encoders not found in: \"{$this->_getProcessOutput($process, true)}\"");
        }
    }

    private function _processDecoderData(Process $process)
    {
        $process->wait();

        $this->_decoders = new FFMPEGDataFilter();
        $clearPattern    = "/------(?<decoders>.*)/s";
        $decoderPattern  = "/(?<decoder>[\w\.].*)[\n\r\f]*/";
        $output          = $this->_getProcessOutput($process);

        if (preg_match($clearPattern, $output, $matches)) {
            $decoders = $matches["decoders"];

            if (preg_match_all($decoderPattern, $decoders, $matches)) {
                foreach ($matches["decoder"] as $decoder) {
                    if (FFMPEGDecoder::match($decoder)) {
                        $this->_decoders->addData(FFMPEGDecoder::from($decoder));
                    }
                }
            }
        }

        if (! $this->_decoders->hasData()) {
            throw new \Exception("FFMPEG decoders not found in: \"{$this->_getProcessOutput($process, true)}\"");
        }
    }

    private function _processCodecData(Process $process)
    {
        $process->wait();

        $this->_codecs = new FFMPEGDataFilter();
        $clearPattern  = "/-------(?<codecs>.*)/s";
        $codecPattern  = "/(?<codec>[\w\.].*)[\n\r\f]*/";
        $output        = $this->_getProcessOutput($process);

        if (preg_match($clearPattern, $output, $matches)) {
            $codecs = $matches["codecs"];

            if (preg_match_all($codecPattern, $codecs, $matches)) {
                foreach ($matches["codec"] as $codec) {
                    if (FFMPEGCodec::match($codec)) {
                        $this->_codecs->addData(FFMPEGCodec::from($codec));
                    }
                }
            }
        }

        if (! $this->_codecs->hasData()) {
            throw new \Exception("FFMPEG codecs not found in: \"{$this->_getProcessOutput($process, true)}\"");
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
        $executable  = $this->_executablePath;
        $args        = $this->_compileArgs();

        return "${executable} ${args}";
    }

    private function _compileArgs()
    {
        $args = "";

        foreach ($this->_options as $option) {
            $args .= $option->toFFMPEGArgOption() . " ";
        }

        $args = mb_substr($args, 0, mb_strlen($args, "UTF-8") - 1, "UTF-8");

        return $args;
    }

    /**
     * Compiles the environment variables of FFMPEGOptions.
     *
     * @param bool $compileOnlyOwn Only compile own environment variables.
     *                             If == false, $_SERVER and $_ENV will be ignored.
     *
     * @return array
     */
    private function _compileEnvVars($compileOnlyOwn = false)
    {
        $env = [];

        if (! $compileOnlyOwn) {
            $env = $_SERVER + $_ENV;
        }

        foreach ($this->_options as $option) {
            $env += $option->toFFMPEGEnvOption();
        }

        return $env;
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

    private function _getProcessOutput(Process $process, $stripNewlines = false)
    {
        $output = strlen($process->getOutput()) > 0 ? $process->getOutput() : $process->getErrorOutput();

        if ($stripNewlines) {
            $output = str_replace("\n", "", $output);
        }

        return $output;
    }

    private function _checkOptions()
    {
        $hasInputOption  = false;
        $hasOutputOption = false;
        $hasCodecOption  = false;

        foreach ($this->_options as $option) {
            $option->check($this);

            if ($option instanceof InputOption) {
                $hasInputOption = true;
            }

            if ($option instanceof OutputOption) {
                $hasOutputOption = true;
            }

            if ($option instanceof CodecOption) {
                $hasCodecOption = true;
            }
        }

        if (! $hasInputOption) {
            throw new FFMPEGNoInputSpecifiedException();
        }

        if (! $hasOutputOption) {
            throw new FFMPEGNoOutputSpecifiedException();
        }

        if (! $hasCodecOption) {
            throw new FFMPEGNoCodecSpecifiedException();
        }
    }

    private function _FireCallback(FFMPEGStatusStruct $struct)
    {
        if ($this->_callback !== null) {
            $this->_callback->__invoke(FFMPEGStatus::from($struct));
        }
    }
}
