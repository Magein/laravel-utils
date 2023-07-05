<?php

namespace Magein\LaravelUtils;

class ApiJson
{
    private $code;

    private $msg;

    private $data;

    public function __construct($code = ApiCode::SUCCESS, $msg = '', $data = null)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;
    }

    /**
     * @return int|mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int|mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed|string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param mixed|string $msg
     */
    public function setMsg($msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed|null $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => $this->data
        ];
    }
}
