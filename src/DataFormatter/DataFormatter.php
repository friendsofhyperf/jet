<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendsofhyperf/jet/blob/main/LICENSE
 */

namespace FriendsOfHyperf\Jet\DataFormatter;

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;

class DataFormatter implements DataFormatterInterface
{
    public function formatRequest($data): array
    {
        [$path, $params, $id] = $data;

        return [
            'jsonrpc' => '2.0',
            'method' => $path,
            'params' => $params,
            'id' => $id,
            'data' => [],
        ];
    }

    public function formatResponse($data): array
    {
        [$id, $result] = $data;

        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => $result,
        ];
    }

    public function formatErrorResponse($data): array
    {
        [$id, $code, $message, $data] = $data;

        if (isset($data) && $data instanceof \Throwable) {
            $data = [
                'class' => get_class($data),
                'code' => $data->getCode(),
                'message' => $data->getMessage(),
            ];
        }

        return [
            'jsonrpc' => '2.0',
            'id' => $id ?? null,
            'error' => [
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ],
        ];
    }
}
