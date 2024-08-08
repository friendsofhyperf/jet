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

namespace FriendsOfHyperf\Jet\Consul;

use FriendsOfHyperf\Jet\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var null|array
     */
    private $decoded;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function __call($name, $arguments)
    {
        return $this->response->{$name}(...$arguments);
    }

    /**
     * @param null|mixed $default
     * @return mixed
     * @throws ServerException
     */
    public function json(?string $key = null, $default = null)
    {
        if (is_null($this->decoded)) {
            if ($this->response->getHeaderLine('Content-Type') !== 'application/json') {
                throw new ServerException(['message' => 'The Content-Type of response is not equal application/json']);
            }

            $this->decoded = json_decode((string) $this->response->getBody(), true);
        }

        if (! $key) {
            return $this->decoded;
        }

        return array_get($this->decoded, $key, $default);
    }

    /**
     * @return null|bool|object
     */
    public function object()
    {
        return json_decode((string) $this->response->getBody());
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
