<?php

namespace DeltaApi\Endpoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface EndpointInterface
{
    /**
     * @param Request $request
     * @return Response
     */
    public function respond(Request $request);
}
