<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Consul;

class Health extends Client
{
    /**
     * Get service
     * @param string $service
     * @param array $options
     * @return Response
     */
    public function service($service = '', $options = array())
    {
        $options = array(
            'query' => $this->resolveOptions($options, array('dc', 'tag', 'passing')),
        );

        return $this->request('GET', '/v1/health/service/' . $service, $options);
    }
}
