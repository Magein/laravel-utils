### 简介

> 使用laravel框架开发习惯使用到的类以及api接口开发的响应

### 命令行

```
# 创建model类且继承MainModel，同时执行model:property
php artisan model:create
# 创建model属性
php artisan model:property
# 创建model验证类
php artisan model:validate
```

可以使用--help查看帮助说明

### MainModel说明

Models下面的模型继承MainModel享有额外的查询功能

```php
/**
  * @method static Order|null _orderNo($order_no);
  * @method static \Illuminate\Pagination\LengthAwarePaginator|null __orderNo($order_no); 
  * @method static \Illuminate\Database\Eloquent\Collection|null ___orderNo($order_no);
  * 
*/

// 查询单个数据 拼接查询条件['order_no'=>$order_no]
$order_no='';
Order::_orderNo($order_no);
// 分页查询 可以在请求中携带page_size参数，也可以在model中重新定义page_size属性值
Order::__orderNo($order_no);
Order::__order_no($order_no);
Order::__order_no__($order_no);

Order::__orderNo($order_no,10);
Order::__orderNo($order_no,['page_size'=>10]);

// 查询全部数据
Order::___orderNo($order_no);
```
