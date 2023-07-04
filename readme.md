### 简介

> laravel项目中常用的一些类

### 命令行

#### 创建model类

> 使用此命令会创建模型类，并且继承BaseModel

```
php artisan model:create
```

#### 创建model属性

```
php artisan model:property
```

#### 创建model验证类

```
php artisan model:validate
```

### BaseModel类

Models下面的模型继承BaseModel享有额外的查询功能

```php
/**
  * @method static Order|null _orderNo($order_no);
  * @method static \Illuminate\Database\Eloquent\Collection|null __orderNo($order_no);
  * @method static \Illuminate\Pagination\LengthAwarePaginator|null ___orderNo($order_no);
*/

// 查询单个数据 拼接查询条件['order_no'=>$order_no]
$order_no='';
Order::_orderNo($order_no);
// 查询全部数据
Order::__orderNo($order_no);
// 分页查询
Order::___orderNo($order_no);
```
