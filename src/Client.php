<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet;

use FriendsOfHyperf\Jet\Exception\RecvFailedException;
use FriendsOfHyperf\Jet\Exception\ServerException;

class Client
{
    /**
     * The Jet major version.
     */
    public const MAJOR_VERSION = 2;

    /**
     * @var Metadata
     */
    protected $metadata;

    public function __construct(Metadata $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \Throwable
     */
    public function __call($name, $arguments)
    {
        $tries = $this->metadata->getTries();
        $path = $this->metadata->getPathGenerator()->generate($this->metadata->getName(), $name);
        $transporter = $this->metadata->getTransporter();
        $dataFormatter = $this->metadata->getDataFormatter();
        $packer = $this->metadata->getPacker();

        if ($transporter->getLoadBalancer()) {
            $nodeCount = count($transporter->getLoadBalancer()->getNodes());
            if ($nodeCount > $tries) {
                $tries = $nodeCount;
            }
        }

        $callback = function () use ($transporter, $dataFormatter, $packer, $path, $arguments) {
            $data = $dataFormatter->formatRequest([$path, $arguments, uniqid()]);

            $transporter->send($packer->pack($data));

            $ret = $transporter->recv();

            if (! is_string($ret)) {
                throw new RecvFailedException('Recv failed');
            }

            return with($packer->unpack($ret), function ($data) {
                if (array_key_exists('result', $data)) {
                    return $data['result'];
                }

                throw new ServerException($data['error'] ?? []);
            });
        };

        return retry($tries, $callback);
    }
}
