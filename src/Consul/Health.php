<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Consul;

class Health extends Client
{
    /**
     * Get service.
     * @param string $service
     * @return Response
     */
    public function service($service = '', array $options = [])
    {
        $params = [
            'query' => $this->resolveOptions($options, ['dc', 'tag', 'passing']),
        ];

        return $this->request('GET', '/v1/health/service/' . $service, $params);
    }
}
