<h1 align="center"> model filter </h1>


## Installing

```shell
$ composer require duc_cnzj/model-filter
```

## Usage

1. 生成filter

> 自动会加上 Filter 后缀

```shell script
php artisan make:filter User
or 
php artisan make:filter UserFilter
```

Model 引入 Filter

```php
# User.php

class User extends Model 
{
    use HasFilter;
}
```

控制器中使用
```php
User::filter($request)->get();
or
User::filter(new UserFilter($request))->get();
or
User::filter(['name' => 'duc'])->get();
```

筛选filter参数
```php
User::filter($request, ['name'])->get();
```

带上前缀
```php
User::filter($request, ['name'], 'user')->get();
```

自定义入参过滤规则
```php
# 默认是
return array_filter($inputs, function ($item) {
    return !is_null($item);
});

# 自定义的话，请在 boot 方法这样写
Filter::setGetFilterCallback('array_filter'); //callback 第一个参数就是inputs
Filter::setGetFilterCallback(function ($items) {
    return array_filter($items, function ($value, $key) {
        return $key !== 'sb';
    }, ARRAY_FILTER_USE_BOTH);
}); //callback 第一个参数就是inputs
```

## Contributing

## License

MIT