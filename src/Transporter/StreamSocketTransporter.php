<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Transporter;

use FriendsOfHyperf\Jet\Exception\ConnectionException;
use FriendsOfHyperf\Jet\Exception\ExceptionThrower;
use FriendsOfHyperf\Jet\Exception\RecvFailedException;
use FriendsOfHyperf\Jet\Support\Util;

class StreamSocketTransporter extends AbstractTransporter
{
    /**
     * @var null|resource
     */
    protected $client;

    /**
     * @var bool
     */
    protected $isConnected = false;

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * @param string $data
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @throws \RuntimeException
     * @return void
     */
    public function send($data)
    {
        $this->connect();
        fwrite($this->client, $data);
    }

    /**
     * @throws \Exception
     * @return string
     */
    public function recv()
    {
        try {
            return $this->receive();
        } catch (\Exception $e) {
            $this->close();
            throw $e;
        }
    }

    /**
     * @return string
     */
    public function receive()
    {
        $buf = '';
        $timeoutMs = $this->timeout > 0 ? $this->timeout * 1000 : 1000;
        $client = $this->client;

        stream_set_blocking($this->client, false);

        // The maximum number of retries is 12, and 1000 microseconds is the minimum waiting time.
        // The waiting time is doubled each time until the server writes data to the buffer.
        // Usually, the data can be obtained within 1 microsecond.
        $result = Util::retry(12, function () use (&$buf, &$timeoutMs, $client) {
            $read = array($client);
            $write = null;
            $except = null;

            while (stream_select($read, $write, $except, 0, $timeoutMs)) {
                foreach ($read as $r) {
                    $res = fread($r, 8192);

                    if (feof($r)) {
                        return new ExceptionThrower(new ConnectionException('Connection was closed.'));
                    }

                    $buf .= $res;
                }
            }

            if (!$buf) {
                $timeoutMs *= 2;

                throw new RecvFailedException('No data was received');
            }

            return $buf;
        });

        if ($result instanceof ExceptionThrower) {
            throw $result->getThrowable();
        }

        return $result;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @return array
     */
    protected function getTarget()
    {
        if ($this->getLoadBalancer()) {
            $node = $this->getLoadBalancer()->select();
        } else {
            $node = $this;
        }

        Util::throwIf(
            !$node->host || !$node->port,
            new \InvalidArgumentException(sprintf('Invalid host %s or port %s.', $node->host, $node->port))
        );

        return array($node->host, $node->port);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @throws \RuntimeException
     * @return void
     */
    protected function connect()
    {
        if ($this->isConnected) {
            return;
        }
        if ($this->client) {
            fclose($this->client);
            unset($this->client);
        }

        Util::retry(5, function () {
            list($host, $port) = $this->getTarget();

            $client = stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, $this->timeout);

            Util::throwIf($client === false, new \RuntimeException(sprintf('[%d] %s', $errno, $errstr)));

            $this->client = $client;
            $this->isConnected = true;
        });
    }

    /**
     * @return void
     */
    protected function close()
    {
        if ($this->client) {
            fclose($this->client);
            $this->client = null;
        }
    }
}
