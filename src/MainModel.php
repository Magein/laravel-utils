<?php

namespace Magein\LaravelUtils;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Magein\PhpUtils\Variable;


/**
 * @method static firstOrCreate($where, $params = [])
 * @method static updateOrCreate($where, $params = [])
 * @method static first()
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static whereDate($column, $value)
 * @method static whereBetween($column, $value)
 * @method static whereIn($column, $value)
 * @method static find($primary_key)
 * @method static pluck($field, $key = '')
 */
class MainModel extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $created_at_format = 'Y-m-d H:i';

    protected $page_size = 15;

    /**
     * @param $name
     * @param $arguments
     * @return mixed|null
     */
    public static function __callStatic($name, $arguments)
    {
        preg_match('/^_{1,3}/', $name, $matches);

        $len = strlen($matches[0] ?? '');

        if ($len > 0) {
            $field = trim(substr($name, $len), '_');
            $field = Variable::ins()->underline($field);

            if (empty($field) || empty($arguments)) {
                return null;
            }
            $value = $arguments[0] ?? null;
            if (empty($value)) {
                return null;
            }
            $params = $arguments[1] ?? [];
            if (!is_array($params)) {
                $params = [];
            }

            $params[$field] = $value;
            if ($len === 1) {
                return static::where($params)->first();
            } elseif ($len === 2) {
                if (isset($arguments[1]) && is_int($arguments[1])) {
                    $page_size = $arguments[1];
                } elseif ($params['page_size'] ?? '') {
                    $page_size = $params['page_size'];
                    unset($params['page_size']);
                } else {
                    $page_size = request()->input('page_size', (new static())->page_size);
                }
                return static::where($params)->paginate($page_size);
            } elseif ($len === 3) {
                return static::where($params)->get();
            }
        }

        return parent::__callStatic($name, $arguments);
    }

    /**
     * 属性转化设置成array json 进行转化
     *
     * 0 = [0]
     * true、false、'' = ''
     * 1、'1' = [1]
     *
     * @param $value
     * @return false|string
     */
    protected function asJson($value)
    {
        if ($value === 0 || $value === "0") {
            $value = [0];
        } elseif (empty($value) || is_bool($value)) {
            return '';
        } elseif (is_int($value) || is_string($value)) {
            $value = [$value];
        }

        return json_encode($value);
    }

    protected function asIntJson($value)
    {
        if (is_array($value)) {
            $value = array_unique($value);
            $value = $value ? array_reduce($value, function ($value, $item) {
                $value[] = intval($item);
                return $value;
            }) : [];
        }
        return $this->asJson($value);
    }

    public function fromJson($value, $asObject = false)
    {
        if (empty($value) || $value == '[]' || $value == '""' || $value === "''") {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        return parent::fromJson($value, $asObject);
    }

    /**
     * @return string
     */
    public function getCreatedAtAttribute(): string
    {
        $created_at = $this->attributes['created_at'] ?? '';

        if (empty($created_at)) {
            return $created_at;
        }

        if ($this->created_at_format) {
            return Date::parse($created_at)->format($this->created_at_format);
        }

        return $created_at;
    }

    /**
     * @return string
     */
    public function getCreatedTextAttribute(): string
    {
        $created_at = $this->attributes['created_at'] ?? '';

        if ($created_at) {
            return Date::parse($created_at)->diffForHumans();
        }

        return '';
    }

    /**
     * @return string
     */
    public function getSexTextAttribute(): string
    {
        $sex = $this->attributes['sex'] ?? 0;

        $data = [
            0 => '保密',
            1 => '男',
            2 => '女',
        ];

        return $data[$sex] ?? '保密';
    }
}
