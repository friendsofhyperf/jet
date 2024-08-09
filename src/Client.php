<?php

namespace Jet;

use Jet\Exception\RecvFailedException;
use Jet\Exception\ServerException;
use Jet\Util as JetUtil;

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
     * @return void
     * @throws \InvalidArgumentException
     * @throws \Exception
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

            JetUtil::throwIf(!is_string($ret), new RecvFailedException('Recv failed'));

            return JetUtil::with($packer->unpack($ret), function ($data) {
                if (array_key_exists('result', $data)) {
                    return $data['result'];
                }

                throw new ServerException(isset($data['error']) ? $data['error'] : array());
            });
        };

        return JetUtil::retry($tries, $callback);
    }
}
