<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet;

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;
use FriendsOfHyperf\Jet\Contract\PackerInterface;
use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;
use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;
use FriendsOfHyperf\Jet\DataFormatter\DataFormatter;
use FriendsOfHyperf\Jet\Packer\JsonEofPacker;
use FriendsOfHyperf\Jet\PathGenerator\PathGenerator;
use FriendsOfHyperf\Jet\Support\Assert;

class Metadata
{
    /**
     * @var null|TransporterInterface
     */
    protected $transporter;

    /**
     * @var null|PackerInterface
     */
    protected $packer;

    /**
     * @var null|DataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var null|PathGeneratorInterface
     */
    protected $pathGenerator;

    /**
     * @var null|RegistryInterface
     */
    protected $registry;

    /**
     * @var int
     */
    protected $tries = 0;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $protocol;

    /**
     * @var int
     */
    protected $timeout = 3;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set protocol.
     * @param string $protocol
     * @return $this
     */
    public function setProtocol($protocol)
    {
        Assert::assertProtocol($protocol);

        $this->protocol = $protocol;

        return $this;
    }

    /**
     * Get protocol.
     * @return null|string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set transporter.
     * @param TransporterInterface $transporter
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setTransporter($transporter)
    {
        Assert::assertTransporter($transporter);

        $this->transporter = $transporter;

        return $this;
    }

    /**
     * Get transporter.
     * @return TransporterInterface
     */
    public function getTransporter()
    {
        if ($this->transporter) {
            return $this->transporter;
        }

        if ($this->registry) {
            return $this->registry->getTransporter($this->name, $this->protocol, $this->timeout);
        }

        throw new \RuntimeException('Transporter not registered yet.');
    }

    /**
     * Set packer.
     * @param PackerInterface $packer
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setPacker($packer)
    {
        Assert::assertPacker($packer);

        $this->packer = $packer;
        
        return $this;
    }

    /**
     * Get packer.
     * @return PackerInterface
     */
    public function getPacker()
    {
        if (is_null($this->packer)) {
            $this->packer = new JsonEofPacker();
        }

        return $this->packer;
    }

    /**
     * Set data formatter.
     * @param DataFormatterInterface $dataFormatter
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setDataFormatter($dataFormatter)
    {
        Assert::assertDataFormatter($dataFormatter);

        $this->dataFormatter = $dataFormatter;

        return $this;
    }

    /**
     * Get data formatter.
     * @return DataFormatterInterface
     */
    public function getDataFormatter()
    {
        if (is_null($this->dataFormatter)) {
            $this->dataFormatter = new DataFormatter();
        }

        return $this->dataFormatter;
    }

    /**
     * Set path generator.
     * @param PathGeneratorInterface $pathGenerator
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setPathGenerator($pathGenerator)
    {
        Assert::assertPathGenerator($pathGenerator);

        $this->pathGenerator = $pathGenerator;

        return $this;
    }

    /**
     * Get path generator.
     * @return PathGeneratorInterface
     */
    public function getPathGenerator()
    {
        if (is_null($this->pathGenerator)) {
            $this->pathGenerator = new PathGenerator();
        }

        return $this->pathGenerator;
    }

    /**
     * Set registry.
     * @param RegistryInterface $registry
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setRegistry($registry)
    {
        Assert::assertRegistry($registry);

        $this->registry = $registry;

        return $this;
    }

    /**
     * Get registry.
     * @return null|RegistryInterface
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * Set tries.
     * @param int $tries
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setTries($tries)
    {
        Assert::assertTries($tries);

        if (!is_null($tries)) {
            $this->tries = (int) $tries;
        }

        return $this;
    }

    /**
     * Get tries.
     * @return int
     */
    public function getTries()
    {
        return (int) $this->tries;
    }

    /**
     * Set timeout.
     * @param int $timeout
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setTimeout($timeout)
    {
        Assert::assertTimeout($timeout);

        if (!is_null($timeout)) {
            $this->timeout = (int) $timeout;
        }

        return $this;
    }

    /**
     * Get timeout.
     * @return int
     */
    public function getTimeout()
    {
        return (int) $this->timeout;
    }
}
