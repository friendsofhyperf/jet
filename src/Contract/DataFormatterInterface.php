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
