<?php

class JetMetadata
{
    /**
     * @var null|JetTransporterInterface
     */
    protected $transporter;

    /**
     * @var null|JetPackerInterface
     */
    protected $packer;

    /**
     * @var null|JetDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var null|JetPathGeneratorInterface
     */
    protected $pathGenerator;

    /**
     * @var null|JetRegistryInterface
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
     */
    public function setProtocol($protocol)
    {
        JetServiceManager::assertProtocol($protocol);

        $this->protocol = $protocol;
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
     * @param JetTransporterInterface $transporter
     * @throws InvalidArgumentException
     */
    public function setTransporter($transporter)
    {
        JetServiceManager::assertTransporter($transporter);

        $this->transporter = $transporter;
    }

    /**
     * Get transporter.
     * @return JetTransporterInterface
     */
    public function getTransporter()
    {
        if ($this->registry) {
            return $this->registry->getTransporter($this->name, $this->protocol, $this->timeout);
        }

        if (!$this->transporter) {
            throw new RuntimeException('Transporter not registered yet.');
        }

        return $this->transporter;
    }

    /**
     * Set packer.
     * @param JetPackerInterface $packer
     * @throws InvalidArgumentException
     */
    public function setPacker($packer)
    {
        JetServiceManager::assertPacker($packer);

        $this->packer = $packer;
    }

    /**
     * Get packer.
     * @return JetPackerInterface
     */
    public function getPacker()
    {
        if (is_null($this->packer)) {
            $this->packer = new JetJsonEofPacker();
        }

        return $this->packer;
    }

    /**
     * Set data formatter.
     * @param JetDataFormatterInterface $dataFormatter
     * @throws InvalidArgumentException
     */
    public function setDataFormatter($dataFormatter)
    {
        JetServiceManager::assertDataFormatter($dataFormatter);

        $this->dataFormatter = $dataFormatter;
    }

    /**
     * Get data formatter.
     * @return JetDataFormatterInterface
     */
    public function getDataFormatter()
    {
        if (is_null($this->dataFormatter)) {
            $this->dataFormatter = new JetDataFormatter();
        }

        return $this->dataFormatter;
    }

    /**
     * Set path generator.
     * @param JetPathGeneratorInterface $pathGenerator
     * @throws InvalidArgumentException
     */
    public function setPathGenerator($pathGenerator)
    {
        JetServiceManager::assertPathGenerator($pathGenerator);

        $this->pathGenerator = $pathGenerator;
    }

    /**
     * Get path generator.
     * @return JetPathGeneratorInterface
     */
    public function getPathGenerator()
    {
        if (is_null($this->pathGenerator)) {
            $this->pathGenerator = new JetPathGenerator();
        }

        return $this->pathGenerator;
    }

    /**
     * Set registry.
     * @param JetRegistryInterface $registry
     * @throws InvalidArgumentException
     */
    public function setRegistry($registry)
    {
        JetServiceManager::assertRegistry($registry);

        $this->registry = $registry;
    }

    /**
     * Get registry.
     * @return null|JetRegistryInterface
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * Set tries.
     * @param int $tries
     * @throws InvalidArgumentException
     */
    public function setTries($tries)
    {
        JetServiceManager::assertTries($tries);

        if (!is_null($tries)) {
            $this->tries = (int) $tries;
        }
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
     * @throws InvalidArgumentException
     */
    public function setTimeout($timeout)
    {
        JetServiceManager::assertTimeout($timeout);

        if (!is_null($timeout)) {
            $this->timeout = (int) $timeout;
        }
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
