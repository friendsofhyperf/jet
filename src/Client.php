<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendsofhyperf/jet/blob/main/LICENSE
 */
namespace FriendsOfHyperf\Jet;

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;
use FriendsOfHyperf\Jet\Contract\PackerInterface;
use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;
use FriendsOfHyperf\Jet\DataFormatter\DataFormatter;
use FriendsOfHyperf\Jet\Exception\RecvFailedException;
use FriendsOfHyperf\Jet\Exception\ServerException;
use FriendsOfHyperf\Jet\Packer\JsonEofPacker;
use FriendsOfHyperf\Jet\PathGenerator\PathGenerator;
use Throwable;

class Client
{
    protected $service;

    /**
     * @var TransporterInterface
     */
    protected $transporter;

    /**
     * @var PackerInterface
     */
    protected $packer;

    /**
     * @var DataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var PathGeneratorInterface
     */
    protected $pathGenerator;

    /**
     * @var int
     */
    protected $tries;

    public function __construct(string $service, TransporterInterface $transporter, ?PackerInterface $packer = null, ?DataFormatterInterface $dataFormatter = null, ?PathGeneratorInterface $pathGenerator = null, ?int $tries = null)
    {
        $this->service = $service;
        $this->transporter = $transporter;
        $this->packer = $packer ?? new JsonEofPacker();
        $this->dataFormatter = $dataFormatter ?? new DataFormatter();
        $this->pathGenerator = $pathGenerator ?? new PathGenerator();
        $this->tries = $tries ?? 1;
    }

    /**
     * @param mixed $name
     * @param mixed $arguments
     * @throws Throwable
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $tries = $this->tries;
        $path = $this->pathGenerator->generate($this->service, $name);
        $transporter = $this->transporter;
        $dataFormatter = $this->dataFormatter;
        $packer = $this->packer;

        if ($this->transporter->getLoadBalancer()) {
            $nodeCount = count($this->transporter->getLoadBalancer()->getNodes());
            if ($nodeCount > $tries) {
                $tries = $nodeCount;
            }
        }

        return retry($tries, function () use ($transporter, $dataFormatter, $packer, $path, $arguments) {
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
        });
    }
}
