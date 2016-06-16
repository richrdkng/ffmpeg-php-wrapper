<?php

namespace FFMPEGWrapper\Shell;

use FFMPEGWrapper\FFMPEG;
use function FFMPEGWrapper\Common\FFMPEGEnvVarArrayToEnvVarObject;

class BashShell extends ShellAdapter
{
    /** @var bool */
    private $_printComments = true;

    /** @var bool */
    private $_printShebang = true;

    /** @var bool */
    private $_changeDirectoryToCWD = true;

    /** @var bool */
    private $_splitLongLinesIntoMultipleLines = true;

    /**
     * @param bool|null $value
     *
     * @return bool
     */
    public function printComments($value = null)
    {
        if (is_bool($value)) {
            $this->_printComments = $value;
        }

        return $this->_printComments;
    }

    /**
     * @param bool|null $value
     *
     * @return bool
     */
    public function printShebang($value = null)
    {
        if (is_bool($value)) {
            $this->_printShebang = $value;
        }

        return $this->_printShebang;
    }

    /**
     * @param bool|null $value
     *
     * @return bool
     */
    public function changeDirectoryToCWD($value = null)
    {
        if (is_bool($value)) {
            $this->_changeDirectoryToCWD = $value;
        }

        return $this->_changeDirectoryToCWD;
    }

    /**
     * @param bool|null $value
     *
     * @return bool
     */
    public function splitLongLinesIntoMultipleLines($value = null)
    {
        if (is_bool($value)) {
            $this->_splitLongLinesIntoMultipleLines = $value;
        }

        return $this->_splitLongLinesIntoMultipleLines;
    }

    /**
     * @param FFMPEG      $ffmpeg
     *
     * @return string
     */
    public function toShellCommand(FFMPEG $ffmpeg)
    {
        $command = "";

        if ($this->printShebang()) {
            $command = "#!/usr/bin/env bash";
            $command .= "\n";
        }

        if ($this->printComments()) {
            $command .= "\n";
            $command .= "# |-----------------------------------------------------------------------------\n";
            $command .= "# | FFMPEG Bash Script\n";
            $command .= "# |-----------------------------------------------------------------------------\n";
            $command .= "\n";
        }

        if ($this->changeDirectoryToCWD()) {
            if ($ffmpeg->getCWD() !== null) {
                if ($this->printComments()) {
                    $command .= "# navigate into CWD";
                    $command .= "\n";
                }

                $command .= "cd {$ffmpeg->getCWD()}";
                $command .= "\n";
            }
        }

        $envVars = [];
        $args    = [];
        $exec    = $ffmpeg->getExecutablePath();
        $execPad = str_repeat(" ", strlen($exec) + 1);

        foreach ($ffmpeg->getOptions() as $option) {
            $envVars[] = $option->toFFMPEGEnvOption();
            $args[]    = $option->toFFMPEGArgOption();
        }

        $envVars = FFMPEGEnvVarArrayToEnvVarObject($envVars);

        $command .= "\n";

        if (count($envVars) > 0) {
            if ($this->printComments()) {
                $command .= "# export FFMPEG-related environment variable(s)";
                $command .= "\n";
            }
        }

        foreach ($envVars as $envVar) {
            $command .= "export {$envVar->key}=\"{$envVar->value}\"";
            $command .= "\n";
        }

        $command .= "\n";
        $command .= "{$exec}";

        for ($i = 0, $bound = count($args) - 1; $i <= $bound; $i++) {
            if ($i === 0) {
                $command .= " ";
            } else {
                if ($this->splitLongLinesIntoMultipleLines()) {
                    $command .= $execPad;
                }
            }

            $command .= $args[$i];

            if ($i !== $bound) {
                if ($this->splitLongLinesIntoMultipleLines()) {
                    $command .= " \\";
                    $command .= "\n";
                }
            } else {
                $command .= "\n";
            }
        }

        $command .= "\n";

        if (count($envVars) > 0) {
            if ($this->printComments()) {
                $command .= "# unset (delete) FFMPEG-related environment variable(s)";
                $command .= "\n";
            }
        }

        foreach ($envVars as $envVar) {
            $command .= "unset {$envVar->key}";
            $command .= "\n";
        }

        return $command;
    }
}
