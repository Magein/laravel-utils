<?php

namespace Magein\Common;

use Magein\PhpUtils\Variable;

class PropertyFilter
{
    /**
     * @var array
     */
    private array $keys = [];

    /**
     * 初始化
     * @param array $params
     */
    public function initial(array $params)
    {
        $variable = new Variable();
        if ($params) {
            foreach ($params as $key => $item) {
                $method = 'set' . $variable->pascal($key);
                if (method_exists($this, $method)) {
                    if ($item === null) {
                        $item = '';
                    }
                    $item = trim($item);
                    // 设置值
                    $this->$method($item);
                    // 保存键
                    $this->keys[] = $key;
                }
            }
        }
    }

    /**
     * 获取数组，这里是获取全部属性的值
     * @return array
     */
    public function toArray(): array
    {
        $values = [];
        if ($this->keys) {
            $variable = new Variable();
            foreach ($this->keys as $key) {
                $method = 'get' . $variable->pascal($key);
                if (method_exists($this, $method)) {
                    // 设置值
                    $value = $this->$method();
                    $value = $value === null ? '' : $value;
                    $values[$key] = $value;
                }
            }
        }
        return $values;
    }

    /**
     * 获取值不为空的数据
     * @return array
     */
    public function values(): array
    {
        $values = [];
        if ($this->keys) {
            $variable = new Variable();
            foreach ($this->keys as $key) {
                $method = 'get' . $variable->pascal($key);
                if (method_exists($this, $method)) {
                    $value = $this->$method();
                    if ($value) {
                        // 设置值
                        $values[$key] = $value;
                    }
                }
            }
        }
        return $values;
    }
}
