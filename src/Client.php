<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet;

use FriendsOfHyperf\Jet\Exception\RecvFailedException;
use FriendsOfHyperf\Jet\Exception\ServerException;
use FriendsOfHyperf\Jet\Support\Util;

class Client
{
    /**
     * The Jet major version.
     */
    const MAJOR_VERSION = 1;

    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     * @param Metadata $metadata
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @return void
     */
    public function __construct($metadata)
    {
        $this->metadata = $metadata;
    }

    public function __call($name, $arguments)
    {
        $tries = $this->metadata->getTries();
        $pathGenerator = $this->metadata->getPathGenerator();
        $transporter = $this->metadata->getTransporter();
        $dataFormatter = $this->metadata->getDataFormatter();
        $packer = $this->metadata->getPacker();
        $path = $pathGenerator->generate($this->metadata->getName(), $name);

        // if ($transporter->getLoadBalancer()) {
        //     $nodeCount = count($transporter->getLoadBalancer()->getNodes());
        //     if ($nodeCount > $tries) {
        //         $tries = $nodeCount;
        //     }
        // }

        $callback = function () use ($transporter, $dataFormatter, $packer, $path, $arguments) {
            $data = $dataFormatter->formatRequest(array($path, $arguments, uniqid()));

            $transporter->send($packer->pack($data));

            $ret = $transporter->recv();

            Util::throwIf(!is_string($ret), new RecvFailedException('Recv failed'));

            return Util::with($packer->unpack($ret), function ($data) {
                if (array_key_exists('result', $data)) {
                    return $data['result'];
                }

                throw new ServerException(isset($data['error']) ? $data['error'] : array());
            });
        };

        return Util::retry($tries, $callback);
    }
}
