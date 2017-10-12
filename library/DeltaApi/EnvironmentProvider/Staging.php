<?php

namespace DeltaApi\EnvironmentProvider;

class Staging implements EnvironmentProviderInterface
{
    public function generateUsername($slug, $environment)
    {
        return sprintf(
            '%s_%s_staging',
            str_replace('-', '_', $slug),
            str_replace('-', '_', $environment)
        );
    }

    public function generateDomainName($slug, $environment)
    {
        return sprintf(
            '%s-%s.staging.deltasys.com',
            str_replace('_', '-', $slug),
            str_replace('_', '-', $environment)
        );
    }

    public function getIsDevEnvironment()
    {
        return false;
    }
}