<?php

namespace DeltaApi\EnvironmentProvider;

interface EnvironmentProviderInterface
{
    public function generateUsername($slug, $environment);

    public function generateDomainName($slug, $environment);

    public function getIsDevEnvironment();
}