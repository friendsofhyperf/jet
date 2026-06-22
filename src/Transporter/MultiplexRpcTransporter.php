<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Transporter;

use Exception;
use FriendsOfHyperf\Jet\Exception\ConnectionException;
use FriendsOfHyperf\Jet\Exception\RecvFailedException;
use RuntimeException;

class MultiplexRpcTransporter extends StreamSocketTransporter
{
    const PING = 'ping';

    const PONG = 'pong';

    /**
     * @return string 
     */    
    public function receive()
    {
        stream_set_blocking($this->client, false);

        while (true) {
            $header = $this->readBytes(4);

            $unpacked = unpack('Nlength', $header);
            $length = $unpacked['length'];

            if ($length < 4) {
                throw new RecvFailedException(sprintf('Invalid package length: %d', $length));
            }
            $body = $this->readBytes($length);
            if (in_array($body, array(self::PING, self::PONG), true)) {
                continue;
            }

            return $header . $body;
        }
    }

    /**
     * @throws Exception
     * @return string
     */
    private function readBytes(int $length)
    {
        $buffer = '';

        while (strlen($buffer) < $length) {
            $read = array($this->client);
            $write = null;
            $except = null;

            $selected = stream_select($read, $write, $except, $this->timeout);
            if ($selected === false) {
                throw new RuntimeException('Failed to select stream.');
            }

            if ($selected === 0) {
                throw new RecvFailedException('Receive timeout.');
            }

            foreach ($read as $stream) {
                $chunk = fread($stream, $length - strlen($buffer));

                if ($chunk === false) {
                    throw new RecvFailedException('Receive failed.');
                }

                if ($chunk === '' && feof($stream)) {
                    throw new ConnectionException('Connection was closed.');
                }

                $buffer .= $chunk;
            }
        }

        return $buffer;
    }
}
