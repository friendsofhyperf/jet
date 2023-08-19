<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/4.x/README.md
 * @contact  huangdijia@gmail.com
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
