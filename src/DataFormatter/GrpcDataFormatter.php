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
use FriendsOfHyperf\Jet\ObjectManager;

class GrpcDataFormatter implements DataFormatterInterface
{
    public function formatRequest($data): array
    {
        [$path, $params, $id] = $data;

        $objHash = spl_object_hash($params[0]);
        ObjectManager::register($objHash, $params[0]);

        return [
            'method' => $path,
            'params' => $objHash,
            'deserialize' => $params[1],
            'id' => $id,
            'data' => [],
        ];
    }

    public function formatResponse($data): array
    {
        [$id, $result] = $data;

        return [
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
            'id' => $id ?? null,
            'error' => [
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ],
        ];
    }
}
