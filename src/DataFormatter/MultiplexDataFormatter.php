<?php

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

        return [
            'id' => $id,
            'path' => $path,
            'data' => $params,
            'extra' => [],
            'context' => [],
        ];
    }

    /**
     * @param array $data 
     * @return array 
     */
    public function formatResponse($data)
    {
        list($id, $result) = $data;

        return [
            'id' => $id,
            'result' => $result,
            'context' => [],
        ];
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

        return [
            'id' => $id,
            'error' => [
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ],
            'context' => [],
        ];
    }
}
