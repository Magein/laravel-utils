<?php

namespace Magein\LaravelUtils\Traits;

use Magein\LaravelUtils\ApiCode;
use Magein\LaravelUtils\ApiJson;
use Magein\LaravelUtils\InvalidParamsException;

trait Error
{
    /**
     * 返回值的两种格式
     * exce 抛出异常  调用需要使用try  一般用于程序内部不需要终止执行的场景
     * http 响应json  用于http请求参数验证
     * normal 返回null  可以通过 getMessage获取信息
     * @var string
     */
    protected string $return_type = 'exce';

    /**
     * @var string
     */
    protected string $message = '';

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @throws InvalidParamsException
     */
    protected function error($message, $code = null, $data = null): ?ApiJson
    {
        if ($this->return_type == 'normal') {
            $this->message = $message;
            return null;
        }

        if ($this->return_type == 'http') {
            return new ApiJson($code ?: ApiCode::INVALID_PARAM, $message, $data);
        }

        throw new InvalidParamsException($message, $code);
    }
}
