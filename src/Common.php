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
     *
     * 日常开发中记录的日志，默认是保存在laravel中，此记录是保存到debug.txt中
     *
     * 需要配置在config/logging.php文件中的channels中配置一下选项
     *
     *'debug' => [
     *  'driver' => 'single',
     *  'path' => storage_path('logs/debug.log'),
     *  'level' => 'debug',
     * ],
     *
     * @param $message
     * @param string|array $data
     * @return bool
     */
    function slog($message, $data = null): bool
    {
        if (is_array($message)) {
            $data = $message;
            $message = '';
        }

        if ($data && !is_array($data)) {
            $data = [$data];
        }

        if (config('logging.channels.debug')) {
            \Illuminate\Support\Facades\Log::channel('debug')->debug($message, $data ?: []);
        } else {
            \Illuminate\Support\Facades\Log::debug($message, $data);
        }

        return true;
    }
}


