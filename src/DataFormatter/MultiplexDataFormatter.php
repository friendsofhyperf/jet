<?php

/*
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\DataFormatter;

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;

class MultiplexDataFormatter implements DataFormatterInterface
{
    /**
     * @param array $data 
     * @return array 
     */
    public function formatRequest($data)
    {
        list($path, $params, $id) = $data;

        return array(
            'id' => $id,
            'path' => $path,
            'data' => $params,
            'extra' => array(),
            'context' => array(),
        );
    }

    /**
     * @param array $data 
     * @return array 
     */
    public function formatResponse($data)
    {
        list($id, $result) = $data;

        return array(
            'id' => $id,
            'result' => $result,
            'context' => array(),
        );
    }

    /**
     * @param array $data 
     * @return array 
     */
    public function formatErrorResponse($data)
    {
        list($id, $code, $message, $data) = $data;

        if (isset($data) && $data instanceof \Throwable) {
            $data = array(
                'class' => get_class($data),
                'code' => $data->getCode(),
                'message' => $data->getMessage(),
            );
        }

        return array(
            'id' => $id,
            'error' => array(
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ),
            'context' => array(),
        );
    }
}
