<?php

namespace FriendsOfHyperf\Jet\Consul;

class Catalog extends Client
{
    /**
     * @param array $options
     * @return Response
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function services($options = array())
    {
        $options = array(
            'query' => $this->resolveOptions($options, array('dc')),
        );

        return $this->request('GET', '/v1/catalog/services', $options);
    }
}
