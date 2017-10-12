<?php

namespace DeltaApi\EnvironmentProvider;

class EnvironmentProviderFactory
{
    public static function createInstance($providerName)
    {
        switch ($providerName) {
            case 'dev':
                return new Dev();
            case 'staging':
                return new Staging();
            default:
                throw new Exception("Invalid provider name '{$providerName}' used.");
        }
    }
}