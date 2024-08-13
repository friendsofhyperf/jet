<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Consul;

class Agent extends Client
{
    /**
     * @param array $service
     * @return Response
     */
    public function registerService($service = array())
    {
        $options = array(
            'body' => $service,
        );

        return $this->request('PUT', '/v1/agent/service/register?replace-existing-checks=true', $options);
    }

    /**
     * @param string $serviceId 
     * @return Response 
     */
    public function deregisterService($serviceId)
    {
        return $this->request('PUT', '/v1/agent/service/deregister/' . $serviceId);
    }
}
