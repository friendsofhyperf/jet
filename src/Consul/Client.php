<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Consul;

use FriendsOfHyperf\Jet\Exception\ClientException;
use FriendsOfHyperf\Jet\Support\Arr;

class Client
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var array
     */
    protected $headers;

    public function __construct($options = array())
    {
        $options = array_merge(array(
            'uri' => 'http://127.0.0.1:8500',
            'timeout' => 2,
            'headers' => array(), // array('X-Consul-Token' => 'your-token')
        ), $options);

        $this->baseUri = rtrim(Arr::get($options, 'uri', ''), '/');
        $this->timeout = Arr::get($options, 'timeout', 1);
        $this->headers = Arr::get($options, 'headers', array());
    }

    /**
     * @param array $options
     * @param array $availableOptions
     * @return array
     */
    protected function resolveOptions($options, $availableOptions)
    {
        // Add key of ACL token to $availableOptions
        $availableOptions[] = 'token';

        return array_intersect_key($options, array_flip($availableOptions));
    }

    /**
     * Request
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return Response
     */
    public function request($method = 'GET', $uri = '', $options = array())
    {
        $headers = array_merge_recursive($this->headers, isset($options['headers']) ? $options['headers'] : array());
        $url = $this->baseUri . '/' . ltrim($uri, '/');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        if (preg_match('/^https:\/\//', $url)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, Arr::get($options, 'form_params', array()));
                break;
            case 'PUT':
                if (Arr::has($options, 'body')) {
                    $body = json_encode(Arr::get($options, 'body', array()));
                    $headers = array_merge_recursive($headers, array(
                        'Content-Type' => 'application/json',
                        'Content-Length' => strlen($body),
                    ));

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                } else {
                    $headers = array_merge_recursive($headers, array(
                        'Content-Type' => 'application/json',
                        'Content-Length' => 0,
                    ));
                }

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case 'DELETE':
                if (Arr::has($options, 'body')) {
                    $body = json_encode(Arr::get($options, 'body', array()));
                    $headers = array_merge_recursive($headers, array(
                        'Content-Type' => 'application/json',
                        'Content-Length' => strlen($body),
                    ));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                } else {
                    $headers = array_merge_recursive($headers, array(
                        'Content-Type' => 'application/json',
                        'Content-Length' => 0,
                    ));
                }

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'GET':
                $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query(Arr::get($options, 'query'));
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            default:
                throw new ClientException("Not support method: {$method}");
                break;
        }

        if ($headers) {
            $modifyHeaders = array();

            foreach ($headers as $k => $v) {
                $modifyHeaders[] = "{$k}: {$v}";
            }

            if ($modifyHeaders) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $modifyHeaders);
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        return new Response($ch);
    }
}
