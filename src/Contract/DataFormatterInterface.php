<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/3.x/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Contract;

interface DataFormatterInterface
{
    /**
     * @param array $data [$path, $params, $id]
     */
    public function formatRequest($data): array;

    /**
     * @param array $data [$id, $result]
     */
    public function formatResponse($data): array;

    /**
     * @param array $data [$id, $code, $message, $exception]
     */
    public function formatErrorResponse($data): array;
}
