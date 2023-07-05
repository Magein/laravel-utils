<?php

namespace Magein\LaravelUtils\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Magein\PhpUtils\Variable;

class MakeModelProperty extends Command
{
    /**
     *
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:property {name?} {--ignore}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Models目录下的模型类类都追加上@property 属性名称';

    protected $help = "Notice：
    参数是数据库的表名称，需要写完整的、正确的表名称
Usage：
    php artisan model:property users        识别Models/User.php文件
    php artisan model:property user*       识别Models/User/*.php文件
    php artisan model:property companies    识别Models/Company.php文件
    php artisan model:property company*   识别Models/Company/*.php文件
    php artisan model:property user_orders  识别Models/User/Order.php文件
    php artisan model:property user_orders --ignore  识别Models/UserOrder.php文件
";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function help()
    {
        $this->info($this->getHelp());
        die();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $ignore = $this->option('ignore');

        if (empty($name)) {
            $this->help();
        }

        $files = [];

        //匹配以*结束的通配符
        if (preg_match('/\*$/', $name)) {
            $dir = Variable::ins()->pascal(substr($name, 0, -1));
            $files = glob("./app/Models/$dir/*.php");
        } else {
            $class_name = $name;
            if (preg_match('/ies$/', $class_name)) {
                $class_name = preg_replace('/ies$/', 'y', $class_name);
            } elseif (preg_match('/s$/', $class_name)) {
                $class_name = substr($class_name, 0, -1);
            }
            $class_name = Variable::ins()->pascal($class_name);
            if ($ignore) {
                $files = glob("./app/Models/$class_name.php");
            } else {
                $options = explode('_', $name);
                $dir = $options[0] ?? '';
                if ($dir) {
                    $dir = Variable::ins()->pascal($dir);
                    $files = glob("./app/Models/{$dir}/{$class_name}.php");
                }
            }
        }

        if (empty($files)) {
            $this->error('没有加载到文件信息，请检查参数');
            $this->help();
            exit();
        }

        foreach ($files as $path) {
            $content = file_get_contents($path);
            $namespace = preg_replace(['/\.\/app/', '/\.php/', '/\//'], ['App', '', '\\\\'], $path);
            if (preg_match('/\* @property/', $content)) {
                $this->comment('continue file : ' . $path);
                continue;
            }

            $cla_name = pathinfo($path, PATHINFO_FILENAME);

            $model = new $namespace();
            $table_name = $model->getTable();
            try {
                $attrs = DB::select("show full columns from $table_name");
            } catch (QueryException $queryException) {
                $this->error("没有检测到{$table_name}表字段信息，请检查表名称");
                $this->help();
                exit(1);
            }

            $property = '/**';
            $property .= "\n";

            $methods = '';
            $methods .= "\n";
            $method_params = function ($prefix, $name, $param) {
                if ($prefix == '__') {
                    $name = '\Illuminate\Pagination\LengthAwarePaginator';
                } elseif ($prefix == '___') {
                    $name = '\Illuminate\Database\Eloquent\Collection';
                } else {
                    $name = Variable::ins()->pascal($name);
                }
                return '* @method static ' . $name . '|null ' . $prefix . Variable::ins()->camelCase($param) . '($' . $param . ');' . "\n";
            };

            if ($attrs) {
                foreach ($attrs as $attr) {

                    $field = $attr->Field;
                    $type = $attr->Type;
                    $key = $attr->Key;

                    if (in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                        continue;
                    }

                    $var = 'string';
                    if (preg_match('/int/', $type)) {
                        $var = 'integer';
                    } elseif (preg_match('/decimal/', $type)) {
                        $var = 'float';
                    }

                    $property .= " * @property $var $" . $field;
                    $property .= "\n";

                    if (preg_match('/_id|_no|phone|email$/', $field) || $key) {
                        $methods .= $method_params('_', $cla_name, $field);
                        $methods .= $method_params('__', $cla_name, $field);
                        $methods .= $method_params('___', $cla_name, $field);
                    } elseif ($type == 'tinyint') {
                        $methods .= $method_params('__', $cla_name, $field);
                        $methods .= $method_params('___', $cla_name, $field);
                    }
                }
            }

            if (preg_match('/extends MainModel/', $content)) {
                $property .= $methods;
            }

            $property .= " */";

            // 替换属性
            $filename = pathinfo($path, PATHINFO_FILENAME);
            $content = preg_replace("/class $filename/", $property . "\n" . "class $filename", $content);
            file_put_contents($path, $content);
            $this->info('success file: ' . $path);
        }

    }
}
