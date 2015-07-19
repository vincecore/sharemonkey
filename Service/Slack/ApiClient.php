<?php

namespace ShareMonkey\Service\Slack;

use GuzzleHttp\ClientInterface;
use React\EventLoop\LoopInterface;
use Slack\ApiClient as BaseApiClient;

class ApiClient extends BaseApiClient
{
    public function __construct(
        LoopInterface $loop,
        $token,
        ClientInterface $httpClient = null
    ) {
        parent::__construct($loop, $httpClient);
        $this->setToken($token);
    }
}
