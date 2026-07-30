<?php

namespace App\Traits;

trait ApiResponser
{
    protected function success($data = null, int $status = 200): array
    {
        return [
            'success' => true,
            'status'  => $status,
            'data'    => $data,
        ];
    }

    protected function error(string $message, int $status = 400): array
    {
        return [
            'success' => false,
            'status'  => $status,
            'message' => $message,
        ];
    }
}
