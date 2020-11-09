<?php

namespace Huangdijia\Jet\Contract;

interface DataFormatterInterface
{
    /**
     * @param array $data [$path, $params, $id]
     * @return array
     */
    public function formatRequest($data): array;

    /**
     * @param array $data [$id, $result]
     * @return array
     */
    public function formatResponse($data): array;

    /**
     * @param array $data [$id, $code, $message, $exception]
     * @return array
     */
    public function formatErrorResponse($data): array;
}
