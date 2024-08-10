<?php

namespace FriendsOfHyperf\Jet\Aspect;

use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Guzzle\ClientFactory;

/**
 * @property array $config
 */
class GuzzleHttpTransporterAspect extends AbstractAspect
{
    public array $classes = [
        'FriendsOfHyperf\Jet\Transporter\GuzzleHttpTransporter::getClient',
    ];

    /**
     * @var ClientFactory
     */
    protected $clientFactory;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        /** @var \FriendsOfHyperf\Jet\Transporter\GuzzleHttpTransporter $instance */
        $instance = $proceedingJoinPoint->getInstance();
        $config = (function() { return $this->config; })->call($instance);

        return $this->clientFactory->create($config);
    }

}