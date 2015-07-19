<?php

namespace ShareMonkey\Service\Slack;

use GuzzleHttp\ClientInterface;
use React\EventLoop\LoopInterface;
use Slack\RealTimeClient as BaseRealTimeClient;

class RealTimeClient extends BaseRealTimeClient
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
