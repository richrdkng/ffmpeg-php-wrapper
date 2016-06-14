<?php

namespace TestCases\GeneralCases\Options;

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\Option\InputOption;
use PHPUnit\Framework\TestCase;

class InputOptionTest extends TestCase {

    public function testOption()
    {
        $ffmpeg = (new FFMPEG())
            ->add(
                new InputOption("input.video")
            );

        $this->assertEquals("{$ffmpeg->getExecutablePath()} -i \"input.video\"", $ffmpeg->getShellScript());
    }
}
