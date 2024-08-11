<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Consul;

use FriendsOfHyperf\Jet\Exception\ClientException;
use FriendsOfHyperf\Jet\Exception\ServerException;
use GuzzleHttp\Exception\GuzzleException;

class Catalog extends Client
{
    /**
     * @return Response
     * @throws ServerException
     * @throws ClientException
     * @throws GuzzleException
     */
    public function services(array $options = [])
    {
        $params = [
            'query' => $this->resolveOptions($options, ['dc']),
        ];

        return $this->request('GET', '/v1/catalog/services', $params);
    }
}
