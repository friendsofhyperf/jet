<?php

/**
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Contract;

interface DataFormatterInterface
{
    /**
     * @param array $data [$path, $params, $id]
     * @return array
     */
    public function formatRequest($data);

    /**
     * @param array $data [$id, $result]
     * @return array
     */
    public function formatResponse($data);

    /**
     * @param array $data [$id, $code, $message, $exception]
     * @return array
     */
    public function formatErrorResponse($data);
}