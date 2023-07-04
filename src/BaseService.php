<?php

namespace Magein\Common;

use Magein\PhpUtils\Traits\Instance;

class BaseService
{
    use Instance;

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
     * @return BaseModel
     */
    protected function model(): BaseModel
    {
        return new BaseModel();
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
