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

use FriendsOfHyperf\Jet\Exception\ClientException;
use FriendsOfHyperf\Jet\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;

class Catalog extends Client
{
    /**
     * @throws ServerException
     * @throws ClientException
     * @throws GuzzleException
     * @return Response
     */
    public function services(array $options = [])
    {
        $params = [
            'query' => $this->resolveOptions($options, ['dc']),
        ];

        return $this->request('GET', '/v1/catalog/services', $params);
    }
}
