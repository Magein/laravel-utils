<?php

namespace Magein\LaravelUtils\Traits;

use Magein\PhpUtils\Traits\Instance;

trait Service
{
    use Instance;

    use Error;

    /**
     * @var string[]
     */
    protected array $order_by = ['id', 'desc'];

    /**
     * @var string|array
     */
    protected $fields = '*';

    /**
     * @var array
     */
    protected array $where = [];

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

    /**
     * @param $page_size
     * @return mixed
     */
    public function getList($page_size = null)
    {
        return $this->model()->where($this->where)
            ->select($this->fields)
            ->orderBy($this->order_by[0] ?: 'id', $this->order_by[1] ?: 'desc')
            ->paginate($page_size);
    }
}
