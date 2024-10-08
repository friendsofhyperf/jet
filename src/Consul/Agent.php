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

class Agent extends Client
{
    public function registerService(array $service): Response
    {
        $params = [
            'body' => json_encode($service),
        ];

        return $this->request('PUT', '/v1/agent/service/register', $params);
    }
}
