<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/3.x/main/README.md
 * @contact  Huangdijia@gmail.com
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
