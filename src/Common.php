<?php

namespace FFMPEGWrapper\Common;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

function getProperty($object, $property, $defaultValue = null)
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

function FFMPEGEnvVarArrayToEnvVarObject(array $envVars)
{
    $object = [];

    foreach ($envVars as $envVar) {
        if (! empty($envVar)) {
            $key   = key($envVar);
            $value = $envVar[$key];

            $object[] = (object)[
                "key"   => $key,
                "value" => $value
            ];
        }
    }

    return $object;
}
