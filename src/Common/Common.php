<?php

namespace FFMPEGWrapper\Common;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class Common {

    public static function getProperty($object, $property, $defaultValue = null)
    {
        static $accessor = null;

        if ($accessor === null) {
            $accessor = PropertyAccess::createPropertyAccessor();
        }

        if ($object !== null) {
            try {
                return $accessor->getValue($object, $property);
            } catch (NoSuchPropertyException $e) {
                // noop
            }
        }

        return $defaultValue;
    }
}
