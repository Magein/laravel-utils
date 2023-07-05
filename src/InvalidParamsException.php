<?php

namespace Magein\LaravelUtils;

class InvalidParamsException extends \Exception
{
    public $code = ApiCode::INVALID_PARAM;
}
