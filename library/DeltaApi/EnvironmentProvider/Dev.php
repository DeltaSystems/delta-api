<?php

namespace DeltaApi\EnvironmentProvider;

class Dev implements EnvironmentProviderInterface
{
    public function generateUsername($slug, $environment)
    {
        return sprintf(
            '%s_%s_dev',
            str_replace('-', '_', $slug),
            str_replace('-', '_', $environment)
        );
    }

    public function generateDomainName($slug, $environment)
    {
        return sprintf(
            '%s-%s.dev.deltasys.com',
            str_replace('_', '-', $slug),
            str_replace('_', '-', $environment)
        );
    }

    public function getIsDevEnvironment()
    {
        return true;
    }
}