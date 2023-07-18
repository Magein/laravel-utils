<?php

namespace Magein\LaravelUtils\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Magein\PhpUtils\Variable;

class MakeModel extends Command
{
    /**
     * 创建的模型会默认继承 MainModel 可以使用 --extend=laravel
     *
     * 下面命令会创建Models/Member/MemberAuth.php
     * php artisan model:create member_auth
     *
     * 下面命令会创建Models/MemberAuth.php
     * php artisan model:create member_auth --ignore
     *
     * 下面命令会创建Models/MemberAuth.php并且继承laravel的model
     * php artisan model:create member_auth --ignore --extend=laravel
     * php artisan model:create member_auth --ignore -E laravel
     *
     *
     * @var string
     */
    protected $signature = 'model:create {name?}  {--l|level} {--e|extend=} {--r|request}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建model类 表名称请使用完成的表名称，如members、companies。
 model类默认继承MainModel，可以指定--extend=laravel继承laravel的EloquentModel
 -L
    --level 可以将使用下划线分割创建目录
 -R
    --request  创建request参数
 ';

    public $help = 'Usage example:
    php artisan model:create companies                  创建Models/Company.php
    php artisan model:create member_auths               创建Models/MemberAuth.php
    php artisan model:create member_auths -L            创建Models/Member/Auth.php
    php artisan model:create member_auths --level       创建Models/Member/Auth.php
    php artisan model:create member_auths -E laravel    创建Models/MemberAuth.php并且继承laravel的model
';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $table_name = $this->argument('name');
        $extend = $this->option('extend');
        $request = $this->option('request');
        $level = $this->option('level');

        if (empty($table_name)) {
            $this->info($this->getHelp());
            exit(1);
        }

        $class_name = $table_name;
        if (preg_match('/ies$/', $class_name)) {
            $class_name = preg_replace('/ies$/', 'y', $class_name);
        } elseif (preg_match('/s$/', $class_name)) {
            $class_name = substr($class_name, 0, -1);
        }

        try {
            $attrs = DB::select("show full columns from $table_name");
        } catch (QueryException $queryException) {
            $this->error('没有检测到表字段信息，请检查表名称,输入完整的表名称');
            $this->info($this->getHelp());
            exit(1);
        }

        $dir = '';
        $name = $table_name;
        $namespace = 'namespace App\Models';

        if ($level) {
            $params = explode('_', $name);
            if (count($params) > 1) {
                $dir = $params[0];
            }
        }

        if ($dir) {
            $path = './app/Models/' . Variable::ins()->pascal($dir);
            if (!is_dir($path)) {
                mkdir($path, 757);
            }
            $namespace .= '\\' . Variable::ins()->pascal($dir);
        } else {
            $path = './app/Models';
        }

        $class_name = $name;
        if (preg_match('/ies$/', $class_name)) {
            $class_name = preg_replace('/ies$/', 'y', $class_name);
        } elseif (preg_match('/s$/', $class_name)) {
            $class_name = substr($class_name, 0, -1);
        }

        $class_name = Variable::ins()->pascal($class_name);
        $filename = $path . '/' . $class_name . '.php';

        $fillable = "[\n";
        if ($attrs) {
            foreach ($attrs as $attr) {

                $field = $attr->Field;

                if (in_array($field, ['id', 'money', 'balance', 'score', 'integral', 'created_at', 'updated_at', 'deleted_at'])) {
                    continue;
                }
                $fillable .= "      '$field',\n";
            }
        }
        $fillable .= "]";

        $call = function () use ($name, $request) {
            $params = [
                'name' => $name
            ];
            $this->call('model:property', $params);
            if ($request) {
                $this->call('model:validate', $params);
            }
        };

        if (is_file($filename)) {
            $this->error('file exists:' . $filename);
            $call();
            exit();
        }

        $extends = 'MainModel';
        $extends_use = 'use Magein\LaravelUtils\MainModel;';

        if ($extend === 'laravel') {
            $extends = 'Model';
            $extends_use = 'use Illuminate\Database\Eloquent\Model;';
        }

        $content = <<<EOF
<?php

$namespace;

$extends_use

class {$class_name} extends $extends
{
    protected \$table='$table_name';

    protected \$fillable = $fillable;
}
EOF;

        file_put_contents($filename, $content);

        $this->info('make model successful');

        $call();
    }
}