<?php

namespace Magein\LaravelUtils\Traits;

use Magein\LaravelUtils\MainModel;
use Magein\PhpUtils\Traits\Instance;

trait Service
{
    use Instance;

    use Error;

    /**
     * @return MainModel
     */
    protected function model(): MainModel
    {
        return new MainModel();
    }

    public static function http()
    {
        $ins = self::ins();
        $ins->return_type = 'http';
        return $ins;
    }

    public static function normal()
    {
        $ins = self::ins();
        $ins->return_type = 'normal';
        return $ins;
    }
}
