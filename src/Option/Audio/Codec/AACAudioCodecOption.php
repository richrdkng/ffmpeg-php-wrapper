<?php

namespace FFMPEGWrapper\Option\Audio\Codec;

class AACAudioCodecOption extends AudioCodecOption {

    const PROFILE_HE_AAC_1    = "aac_he";
    const PROFILE_HE_AAC_2    = "aac_he_v2";
    const PROFILE_UNSPECIFIED = null;
    const PROFILE_DEFAULT     = self::PROFILE_UNSPECIFIED;

    /** @var string|null  */
    private $_profile;

    /**
     * AACAudioCodecOption constructor.
     *
     * @param string|null $profile
     *
     * @throws \Exception
     */
    public function __construct($profile = null)
    {
        if ($profile === null) {
            $this->_profile = self::PROFILE_DEFAULT;
        } else {
            switch ($profile) {
                case self::PROFILE_HE_AAC_1:
                case self::PROFILE_HE_AAC_2:
                case self::PROFILE_UNSPECIFIED:
                case self::PROFILE_DEFAULT:
                    $this->_profile = $profile;
                    break;

                default:
                    throw new \Exception("Unknown AAC profile: \"{$profile}\"");
            }
        }
    }

    /**
     * @return string
     */
    function toFFMPEGAudioArgOption()
    {
        $option = "-c:a libfdk_aac";

        if ($this->_profile !== self::PROFILE_DEFAULT) {
            $option .= " -profile:a {$this->_profile}";
        }

        return $option;
    }
}
