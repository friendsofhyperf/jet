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
    protected $transporter = null;

    /**
     * @var null|PackerInterface
     */
    protected $packer = null;

    /**
     * @var null|DataFormatterInterface
     */
    protected $dataFormatter = null;

    /**
     * @var null|PathGeneratorInterface
     */
    protected $pathGenerator = null;

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
    protected $protocol = null;

    /**
     * @var int
     */
    protected $timeout = 3;

    public function __construct($name = '')
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function withName($name)
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
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
     * @param string $protocol
     * @return static
     */
    public function withProtocol($protocol)
    {
        $clone = clone $this;
        $clone->protocol = $protocol;

        return $clone;
    }

    /**
     * Set protocol.
     * @deprecated use withProtocol instead, will be removed in v4.0
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
     * @param TransporterInterface $transporter
     * @return static
     */
    public function withTransporter($transporter)
    {
        $clone = clone $this;
        $clone->transporter = $transporter;

        return $clone;
    }

    /**
     * Set transporter.
     * @deprecated use withTransporter instead, will be removed in v4.0
     * @param TransporterInterface $transporter
     * @throws \InvalidArgumentException
     * @return $this
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
     * @param PackerInterface $packer
     * @return static
     */
    public function withPacker($packer)
    {
        $clone = clone $this;
        $clone->packer = $packer;

        return $clone;
    }

    /**
     * Set packer.
     * @deprecated use withPacker instead, will be removed in v4.0
     * @param PackerInterface $packer
     * @throws \InvalidArgumentException
     * @return $this
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
     * @param DataFormatterInterface $dataFormatter
     * @return static
     */
    public function withDataFormatter($dataFormatter)
    {
        $clone = clone $this;
        $clone->dataFormatter = $dataFormatter;

        return $clone;
    }

    /**
     * Set data formatter.
     * @deprecated use withDataFormatter instead, will be removed in v4.0
     * @param DataFormatterInterface $dataFormatter
     * @throws \InvalidArgumentException
     * @return $this
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
     * @param PathGeneratorInterface $pathGenerator
     * @return static
     */
    public function withPathGenerator($pathGenerator)
    {
        $clone = clone $this;
        $clone->pathGenerator = $pathGenerator;

        return $clone;
    }

    /**
     * Set path generator.
     * @deprecated use withPathGenerator instead, will be removed in v4.0
     * @param PathGeneratorInterface $pathGenerator
     * @throws \InvalidArgumentException
     * @return $this
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
     * @param RegistryInterface $registry
     * @return static
     */
    public function withRegistry($registry)
    {
        $clone = clone $this;
        $clone->registry = $registry;

        return $clone;
    }

    /**
     * Set registry.
     * @deprecated use withRegistry instead, will be removed in v4.0
     * @param RegistryInterface $registry
     * @throws \InvalidArgumentException
     * @return $this
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
     * @param int $tries
     * @return static
     */
    public function withTries($tries)
    {
        $clone = clone $this;
        $clone->tries = $tries;

        return $clone;
    }

    /**
     * Set tries.
     * @deprecated use withTries instead, will be removed in v4.0
     * @param int $tries
     * @throws \InvalidArgumentException
     * @return $this
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
     * @param int $timeout
     * @return static
     */
    public function withTimeout($timeout)
    {
        $clone = clone $this;
        $clone->timeout = $timeout;

        return $clone;
    }

    /**
     * Set timeout.
     * @deprecated use withTimeout instead, will be removed in v4.0
     * @param int $timeout
     * @throws \InvalidArgumentException
     * @return $this
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
