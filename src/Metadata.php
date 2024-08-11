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

    /**
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

    /**
     * Set protocol.
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

    /**
     * Set transporter.
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

    /**
     * Set packer.
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

    /**
     * Set data formatter.
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
     */
    public function getPathGenerator(): PathGeneratorInterface
    {
        if (is_null($this->pathGenerator)) {
            $this->pathGenerator = new PathGenerator();
        }

        return $this->pathGenerator;
    }

    /**
     * Set registry.
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
     */
    public function getTries(): int
    {
        return $this->tries;
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
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set transporter config.
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
