<?php

namespace TestCases\GeneralCases\Options;

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\CodecOption;
use PHPUnit\Framework\TestCase;

class CodecOptionTest extends TestCase {

    public function testOptionWithOptionCopy()
    {
        $ffmpeg = (new FFMPEG())
            ->add(
                new CodecOption(CodecOption::COPY)
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -codec copy", $ffmpeg->getCommandLineArguments());
    }
}
