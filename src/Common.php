<?php

if (!function_exists('isLocal')) {
    /**
     * @return bool
     */
    function isLocal(): bool
    {
        if (app()->environment() == 'local') {
            return true;
        }
        return false;
    }
}

if (!function_exists('domain')) {
    /**
     * @return mixed|string
     */
    function domain()
    {
        return ($_SERVER['HTTP_HOST'] ?? '') ?: $_SERVER['SERVER_NAME'] ?? '';
    }
}

if (!function_exists('slog')) {
    /**
     * @param $message
     * @param string|array $data
     * @return bool
     */
    function slog($message, $data = ''): bool
    {
        if ($message && empty($data)) {
            $data = [$message];
            $message = 'debug';
        } elseif ($message && $data) {
            if (!is_array($data)) {
                $data = [$data];
            }
        } else {
            $message = 'debug';
            $data = [];
        }

        \Illuminate\Support\Facades\Log::debug($message, $data);

        return true;
    }
}


