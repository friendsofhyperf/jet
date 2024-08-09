<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\DataFormatter;

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;
use Throwable;

class MultiplexDataFormatter implements DataFormatterInterface
{
    /**
     * @param array $data
     */
    public function formatRequest($data): array
    {
        [$path, $params, $id] = $data;

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
     */
    public function formatResponse($data): array
    {
        [$id, $result] = $data;

        return [
            'id' => $id,
            'result' => $result,
            'context' => [],
        ];
    }

    /**
     * @param array $data
     */
    public function formatErrorResponse($data): array
    {
        [$id, $code, $message, $data] = $data;

        if (isset($data) && $data instanceof Throwable) {
            $data = [
                'class' => get_class($data),
                'code' => $data->getCode(),
                'message' => $data->getMessage(),
            ];
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
