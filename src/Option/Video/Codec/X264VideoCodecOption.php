<?php

namespace FFMPEGWrapper\Option\Video\Codec;

class X264VideoCodecOption extends VideoCodecOption {

    const PRESET_ULTRAFAST = "ultrafast";
    const PRESET_SUPERFAST = "superfast";
    const PRESET_VERYFAST  = "veryfast";
    const PRESET_FASTER    = "faster";
    const PRESET_FAST      = "fast";
    const PRESET_MEDIUM    = "medium";
    const PRESET_SLOW      = "slow";
    const PRESET_SLOWER    = "slower";
    const PRESET_VERYSLOW  = "veryslow";
    const PRESET_PLACEBO   = "placebo";
    const PRESET_DEFAULT   = self::PRESET_MEDIUM;

    const PROFILE_BASELINE    = "baseline";
    const PROFILE_MAIN        = "main";
    const PROFILE_HIGH        = "high";
    const PROFILE_HIGH10      = "high10";
    const PROFILE_HIGH422     = "high422";
    const PROFILE_HIGH444     = "high444";
    const PROFILE_UNSPECIFIED = null;
    const PROFILE_DEFAULT     = self::PROFILE_UNSPECIFIED;

    const LEVEL_3_0         = "3.0";
    const LEVEL_3_1         = "3.1";
    const LEVEL_4_0         = "4.0";
    const LEVEL_4_1         = "4.1";
    const LEVEL_4_2         = "4.2";
    const LEVEL_UNSPECIFIED = null;
    const LEVEL_DEFAULT     = self::LEVEL_UNSPECIFIED;

    private $_preset;
    private $_profile;
    private $_level;

    /**
     * X264VideoCodecOption constructor.
     *
     * @param string|null $preset
     * @param string|null $profile
     * @param string|null $level
     *
     * @throws \Exception
     */
    public function __construct($preset = null, $profile = null, $level = null)
    {
        if ($preset === null) {
            $this->_preset = self::PRESET_DEFAULT;
        } else {
            switch ($preset) {
                case self::PRESET_ULTRAFAST:
                case self::PRESET_SUPERFAST:
                case self::PRESET_VERYFAST:
                case self::PRESET_FASTER:
                case self::PRESET_FAST:
                case self::PRESET_MEDIUM:
                case self::PRESET_SLOW:
                case self::PRESET_SLOWER:
                case self::PRESET_VERYSLOW:
                case self::PRESET_PLACEBO:
                case self::PRESET_DEFAULT:
                    $this->_preset = $preset;
                    break;

                default:
                    throw new \Exception("Unknown x264 preset: \"{$preset}\"");
            }
        }

        if ($profile === null) {
            $this->_profile = self::PROFILE_DEFAULT;
        } else {
            switch ($profile) {
                case self::PROFILE_BASELINE:
                case self::PROFILE_MAIN:
                case self::PROFILE_HIGH:
                case self::PROFILE_HIGH10:
                case self::PROFILE_HIGH422:
                case self::PROFILE_HIGH444:
                case self::PROFILE_UNSPECIFIED:
                case self::PROFILE_DEFAULT:
                    $this->_profile = $profile;
                    break;

                default:
                    throw new \Exception("Unknown x264 profile: \"{$profile}\"");
            }
        }

        if ($level === null) {
            $this->_level = self::LEVEL_DEFAULT;
        } else {
            switch ($level) {
                case self::LEVEL_3_0:
                case self::LEVEL_3_1:
                case self::LEVEL_4_0:
                case self::LEVEL_4_1:
                case self::LEVEL_4_2:
                case self::LEVEL_UNSPECIFIED:
                case self::LEVEL_DEFAULT:
                    $this->_level = $level;
                    break;

                default:
                    throw new \Exception("Unknown x264 level: \"{$level}\"");
            }
        }
    }

    /**
     * @return string
     */
    function toFFMPEGVideoArgOption()
    {
        $option = "-c:v libx264";

        if ($this->_preset !== self::PRESET_DEFAULT) {
            $option .= " -preset {$this->_preset}";
        }

        if ($this->_profile !== self::PROFILE_DEFAULT) {
            $option .= " -profile:v {$this->_profile}";
        }

        if ($this->_level !== self::LEVEL_DEFAULT) {
            $option .= " -level {$this->_level}";
        }

        return $option;
    }
}
