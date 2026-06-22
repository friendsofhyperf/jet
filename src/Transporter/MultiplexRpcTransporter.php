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

use FriendsOfHyperf\Jet\Exception\ConnectionException;
use FriendsOfHyperf\Jet\Exception\RecvFailedException;

class MultiplexRpcTransporter extends StreamSocketTransporter
{
    public const PING = 'ping';

    public const PONG = 'pong';

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
            if (in_array($body, [self::PING, self::PONG], true)) {
                continue;
            }

            return $header . $body;
        }
    }

    /**
     * @throws \Exception
     */
    private function readBytes(int $length): string
    {
        $buffer = '';

        while (strlen($buffer) < $length) {
            $read = [$this->client];
            $write = null;
            $except = null;

            $selected = stream_select($read, $write, $except, $this->timeout);
            if ($selected === false) {
                throw new \RuntimeException('Failed to select stream.');
            }

            if ($selected === 0) {
                throw new RecvFailedException('Receive timeout.');
            }

            foreach ($read as $stream) {
                /** @var false|string $chunk */
                $chunk = fread($stream, $length - strlen($buffer));

                if ($chunk === false) {
                    throw new RecvFailedException('Receive failed.');
                }

                if ($chunk === '' && feof($stream)) {
                    throw new ConnectionException('Connection was closed.');
                }

                $buffer .= $chunk;
            }
            var_dump($buffer);
        }

        return $buffer;
    }
}
