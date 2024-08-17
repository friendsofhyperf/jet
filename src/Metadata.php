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

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;
use FriendsOfHyperf\Jet\Contract\PackerInterface;
use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;
use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;
use FriendsOfHyperf\Jet\DataFormatter\DataFormatter;
use FriendsOfHyperf\Jet\Packer\JsonEofPacker;
use FriendsOfHyperf\Jet\PathGenerator\PathGenerator;
use FriendsOfHyperf\Jet\Support\Traits\Conditionable;

class Metadata
{
    use Conditionable;

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

    public function __construct(string $name = '')
    {
        $this->name = $name;
    }

    /**
     * @return static
     */
    public function withName(string $name)
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
     * @return static
     */
    public function withProtocol(string $protocol)
    {
        $clone = clone $this;
        $clone->protocol = $protocol;

        return $clone;
    }

    /**
     * Set protocol.
     * @deprecated use withProtocol instead, will be removed in v4.0
     * @return $this
     */
    public function setProtocol(string $protocol)
    {
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
     * @return static
     */
    public function withTransporter(TransporterInterface $transporter)
    {
        $clone = clone $this;
        $clone->transporter = $transporter;

        return $clone;
    }

    /**
     * Set transporter.
     * @deprecated use withTransporter instead, will be removed in v4.0
     * @return $this
     */
    public function setTransporter(TransporterInterface $transporter)
    {
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
     * @return static
     */
    public function withPacker(PackerInterface $packer)
    {
        $clone = clone $this;
        $clone->packer = $packer;

        return $clone;
    }

    /**
     * Set packer.
     * @deprecated use withPacker instead, will be removed in v4.0
     * @return $this
     */
    public function setPacker(PackerInterface $packer)
    {
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
     * @return static
     */
    public function withDataFormatter(DataFormatterInterface $dataFormatter)
    {
        $clone = clone $this;
        $clone->dataFormatter = $dataFormatter;

        return $clone;
    }

    /**
     * Set data formatter.
     * @deprecated use withDataFormatter instead, will be removed in v4.0
     * @return $this
     */
    public function setDataFormatter(DataFormatterInterface $dataFormatter)
    {
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
     * @return static
     */
    public function withPathGenerator(PathGeneratorInterface $pathGenerator)
    {
        $clone = clone $this;
        $clone->pathGenerator = $pathGenerator;

        return $clone;
    }

    /**
     * Set path generator.
     * @return $this
     */
    public function setPathGenerator(PathGeneratorInterface $pathGenerator)
    {
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
     * @return static
     */
    public function withRegistry(RegistryInterface $registry)
    {
        $clone = clone $this;
        $clone->registry = $registry;

        return $clone;
    }

    /**
     * Set registry.
     * @deprecated use withRegistry instead, will be removed in v4.0
     * @return $this
     */
    public function setRegistry(RegistryInterface $registry)
    {
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
     * @return static
     */
    public function withTries(int $tries)
    {
        $clone = clone $this;
        $clone->tries = $tries;

        return $clone;
    }

    /**
     * Set tries.
     * @return $this
     */
    public function setTries(int $tries)
    {
        $this->tries = $tries;

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
     * @return static
     */
    public function withTimeout(int $timeout)
    {
        $clone = clone $this;
        $clone->timeout = $timeout;

        return $clone;
    }

    /**
     * Set timeout.
     * @return $this
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;

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
