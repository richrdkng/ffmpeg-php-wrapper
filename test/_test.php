#!/usr/bin/env php
<?php

require_once realpath(".") . "/vendor/autoload.php";

define("SAMPLES", "/vagrant/tests/samples");
define("OUTPUT", "/vagrant/tests/output");

use FFMPEGWrapper\FFMPEG;
use FFMPEGWrapper\FFMPEGStatus;
use FFMPEGWrapper\Option\Audio\AudioOption;
use FFMPEGWrapper\Option\Audio\Bitrate\CBRAudioBitrateOption;
use FFMPEGWrapper\Option\Audio\Codec\AACAudioCodecOption;
use FFMPEGWrapper\Option\InputOption;
use FFMPEGWrapper\Option\InputSeekOption;
use FFMPEGWrapper\Option\OutputOption;
use FFMPEGWrapper\Option\OverwriteOutputOption;
use FFMPEGWrapper\Option\PassOption;
use FFMPEGWrapper\Option\TimeOption;
use FFMPEGWrapper\Option\Video\Bitrate\ABRVideoBitrateOption;
use FFMPEGWrapper\Option\Video\Codec\X264VideoCodecOption;
use FFMPEGWrapper\Option\Video\VideoOption;

//FFMPEG::runWith("-h");

/*
$en = new FFMPEG();
$en->run();
*/

/*
$di = new DateInterval("10:00:00");

var_dump($di);

return;
*/

//var_dump(php_uname("s"));
// return;

$f = new FFMPEG(
    FFMPEG::DEFAULT_EXECUTABLE_PATH,
    [
        "cwd" => OUTPUT . "/video/"
    ]
);

//var_dump($f->getBuildConfigurations());
var_dump($f->hasBuildConfigurations([
    "name" => "--enable-libfdk-aac"
]));

/*
$f->add(
    new InputSeekOption("00:00:30"),
    new InputOption(SAMPLES . "/video/big_buck_bunny_1080p_h264.mov"),
    new TimeOption("00:00:30"),
    new VideoOption(
        new X264VideoCodecOption(),
        new ABRVideoBitrateOption("1000k")
    ),
    new AudioOption(
        //new AACAudioCodecOption(),
        AudioOption::COPY,
        new CBRAudioBitrateOption("128k")
    ),
    //new PassOption(1, "mp4")
    new OutputOption(OUTPUT . "/video/big_buck_bunny_1080p_h264.mp4")
);

var_dump($f->getShellScript());

$f->run(function(FFMPEGStatus $status) {
    //echo "\n<<< callback callable >>>\n";

    if ($status->isStarted()) {
        echo "\n<<< isStarted >>>\n";
    }

    if ($status->isProgress()) {
        echo "\n<<< isProgress: {$status->getCurrentPercent()} --- {$status->getETA()} >>>\n";
    }

    if ($status->isEnded()) {
        echo "\n<<< isEnded >>>\n";
    }
});
*/
