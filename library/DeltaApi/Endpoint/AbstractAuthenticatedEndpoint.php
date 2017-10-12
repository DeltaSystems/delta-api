<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Db\Row;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAuthenticatedEndpoint implements EndpointInterface
{
    const AUTHENTICATION_FAILED = 'authentication-failed';

    /**
     * @var Silex
     */
    protected $silex;

    /**
     * @var Users
     */
    protected $usersModel;

    /**
     * AbstractAuthenticatedEndpoint constructor.
     * @param Silex $silex
     * @param Users $usersModel
     */
    public function __construct(Silex $silex, Users $usersModel)
    {
        $this->silex      = $silex;
        $this->usersModel = $usersModel;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function respond(Request $request)
    {
        $apiKey = $request->headers->get('php-auth-pw');
        $user   = $this->usersModel->findByApiKey($apiKey);

        if ($user) {
            $response = $this->respondToAuthenticatedRequest($request, $user);
        } else {
            $response = $this->silex->json(
                [
                    'message' => 'API key authentication failed.',
                    'code'    => self::AUTHENTICATION_FAILED
                ],
                401
            );
        }

        return $response;
    }

    abstract public function respondToAuthenticatedRequest(Request $request, Row $user);

    protected function runCurlOverSsh($endpoint, array $params)
    {
        $curlCommand = sprintf(
            'curl --data %s %s',
            escapeshellarg($this->getPostData($params)),
            escapeshellarg('http://localhost:3456' . $endpoint)
        );

        $sshCommand = sprintf(
            'ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -o BatchMode=yes -i %s '
            . 'deltaenv@staging.servers.deltasys.com %s 2>&1',
            escapeshellarg($this->getSshKeyPath()),
            escapeshellarg($curlCommand)
        );

        exec($sshCommand, $output, $exitStatus);

        if ($exitStatus) {
            \Dewdrop\Pimple::getResource('monolog')->error(
                sprintf(
                    'SSH command `%s` failed with this output: %s',
                    $sshCommand,
                    implode(PHP_EOL, $output)
                )
            );
        }
    }

    private function getSshKeyPath()
    {
        if ('apache' === trim(shell_exec('whoami'))) {
            return '/var/www/vhosts/deploy.deltasys.com/apache-ssh-keys/id_rsa';
        } else {
            return realpath(__DIR__ . '/../../../ssh-keys/id_rsa');
        }
    }

    private function getPostData(array $params)
    {
        return http_build_query($params);
    }
}
