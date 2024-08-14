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
use RuntimeException;

class Metadata
{
    protected ?TransporterInterface $transporter = null;

    protected array $transporterConfig = [];

    protected ?PackerInterface $packer = null;

    protected ?DataFormatterInterface $dataFormatter = null;

    protected ?PathGeneratorInterface $pathGenerator = null;

    protected ?RegistryInterface $registry = null;

    protected int $tries = 0;

    protected ?string $protocol = null;

    protected int $timeout = 3;

    public function __construct(protected string $name = '')
    {
    }

    public function withName(string $name): static
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @deprecated use withName instead
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function withProtocol(string $protocol): static
    {
        $clone = clone $this;
        $clone->protocol = $protocol;

        return $clone;
    }

    /**
     * Set protocol.
     * @deprecated use withProtocol instead
     * @return $this
     */
    public function setProtocol(string $protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * Get protocol.
     */
    public function getProtocol(): ?string
    {
        return $this->protocol;
    }

    public function withTransporter(TransporterInterface $transporter): static
    {
        $clone = clone $this;
        $clone->transporter = $transporter;

        return $clone;
    }

    /**
     * Set transporter.
     * @deprecated use withTransporter instead
     * @return $this
     */
    public function setTransporter(TransporterInterface $transporter)
    {
        $this->transporter = $transporter;
        return $this;
    }

    /**
     * Get transporter.
     */
    public function getTransporter(): TransporterInterface
    {
        if ($this->transporter) {
            return $this->transporter;
        }

        if ($this->registry) {
            return $this->registry->getTransporter(
                $this->name,
                $this->protocol,
                array_merge_recursive(
                    $this->transporterConfig,
                    ['timeout' => $this->timeout]
                )
            );
        }

        throw new RuntimeException('Transporter not registered yet.');
    }

    public function withPacker(PackerInterface $packer): static
    {
        $clone = clone $this;
        $clone->packer = $packer;

        return $clone;
    }

    /**
     * Set packer.
     * @deprecated use withPacker instead
     * @return $this
     */
    public function setPacker(PackerInterface $packer)
    {
        $this->packer = $packer;
        return $this;
    }

    /**
     * Get packer.
     */
    public function getPacker(): PackerInterface
    {
        if (is_null($this->packer)) {
            $this->packer = new JsonEofPacker();
        }

        return $this->packer;
    }

    public function withDataFormatter(DataFormatterInterface $dataFormatter): static
    {
        $clone = clone $this;
        $clone->dataFormatter = $dataFormatter;

        return $clone;
    }

    /**
     * Set data formatter.
     * @deprecated use withDataFormatter instead
     * @return $this
     */
    public function setDataFormatter(DataFormatterInterface $dataFormatter)
    {
        $this->dataFormatter = $dataFormatter;
        return $this;
    }

    /**
     * Get data formatter.
     */
    public function getDataFormatter(): DataFormatterInterface
    {
        if (is_null($this->dataFormatter)) {
            $this->dataFormatter = new DataFormatter();
        }

        return $this->dataFormatter;
    }

    public function withPathGenerator(PathGeneratorInterface $pathGenerator): static
    {
        $clone = clone $this;
        $clone->pathGenerator = $pathGenerator;

        return $clone;
    }

    /**
     * Set path generator.
     * @deprecated use withPathGenerator instead
     * @return $this
     */
    public function setPathGenerator(PathGeneratorInterface $pathGenerator)
    {
        $this->pathGenerator = $pathGenerator;
        return $this;
    }

    /**
     * Get path generator.
     */
    public function getPathGenerator(): PathGeneratorInterface
    {
        if (is_null($this->pathGenerator)) {
            $this->pathGenerator = new PathGenerator();
        }

        return $this->pathGenerator;
    }

    public function withRegistry(RegistryInterface $registry): static
    {
        $clone = clone $this;
        $clone->registry = $registry;

        return $clone;
    }

    /**
     * Set registry.
     * @deprecated use withRegistry instead
     * @return $this
     */
    public function setRegistry(RegistryInterface $registry)
    {
        $this->registry = $registry;
        return $this;
    }

    /**
     * Get registry.
     */
    public function getRegistry(): ?RegistryInterface
    {
        return $this->registry;
    }

    public function withTries(int $tries): static
    {
        $clone = clone $this;
        $clone->tries = $tries;

        return $clone;
    }

    /**
     * Set tries.
     * @deprecated use withTries instead
     * @return $this
     */
    public function setTries(int $tries)
    {
        $this->tries = $tries;
        return $this;
    }

    /**
     * Get tries.
     */
    public function getTries(): int
    {
        return $this->tries;
    }

    public function withTimeout(int $timeout): static
    {
        $clone = clone $this;
        $clone->timeout = $timeout;

        return $clone;
    }

    /**
     * Set timeout.
     * @deprecated use withTimeout instead
     * @return $this
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Get timeout.
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function withTransporterConfig(array $config): static
    {
        $clone = clone $this;
        $clone->transporterConfig = $config;

        return $clone;
    }

    /**
     * Set transporter config.
     * @deprecated use withTransporterConfig instead
     * @return $this
     */
    public function setTransporterConfig(array $config = [])
    {
        $this->transporterConfig = $config;
        return $this;
    }

    /**
     * Get transporter config.
     */
    public function getTransporterConfig(): array
    {
        return $this->transporterConfig;
    }
}
