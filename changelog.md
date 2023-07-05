### 修改日志


#### v0.0.4

1. 修改HelperServiceProvider加载方式为composer.json中extra的方式
```json
{
  "extra": {
    "laravel": {
      "providers": [
        "Magein\\LaravelUtils\\Providers\\HelperServiceProvider"
      ]
    }
  }
}
```

#### v0.0.3

1. 优化MakeModelProperty.php
2. MainModel.php增加page_size属性