[apc()](http://twinh.github.com/widget/api/apc)
===============================================

Apc微件是对PHP APC缓存的管理

##### 目录
* apc( $key, $value [ $expire ] )
* apc( $key )
* apc->get( $key )
* apc->set( $key, $value )
* apc->remove( $key ) 
* apc->exists( $key )
* apc->add( $key, $value )
* apc->replace( $key, $value )
* apc->increment ( $key )
* apc->decrement( $key )

### 设置一项PHP APC缓存
```php
bool apc( $key, $value [ $expire ] )
```

##### 参数
* **$key** `string` 缓存的键名
* **$value** `mixed` 缓存的值,允许任意类型
* **$expire** `int` 缓存的有效期,默认为0秒,表示永不过期


该调用方式相等于`$widget->apc->set($key, $value, $expire)`


##### 代码范例
设置键名为"key",值为"value"的缓存
```php
<?php

$widget->apc('key', 'value');

echo $widget->apc('key');
```
##### 运行结果
```php
'value'
```
- - - -

### 获取一项PHP APC缓存
```php
bool apc( $key )
```

##### 参数
* **$key** `string` 缓存的键名


该调用方式相等于`$widget->apc->get($key)`


##### 代码范例
获取键名为"key"的缓存,并打印出来
```php
<?php

$widget->apc('key', 'value');

echo $widget->apc('key');
```
##### 运行结果
```php
'value'
```
- - - -

### 获取一项缓存
```php
mixed apc->get( $key )
```

##### 参数
*无*

- - - -

### 设置一项缓存
```php
bool apc->set( $key, $value )
```

##### 参数
*无*

- - - -

### 移除一项缓存
```php
bool apc->remove( $key ) 
```

##### 参数
*无*

- - - -

### 检查缓存是否存在
```php
bool apc->exists( $key )
```

##### 参数
*无*

- - - -

### 增加一项缓存,如果缓存已存在,返回false
```php
bool apc->add( $key, $value )
```

##### 参数
*无*

- - - -

### 替换一项缓存,如果缓存**不**存在,返回false
```php
bool apc->replace( $key, $value )
```

##### 参数
*无*

- - - -

### 增加一项缓存的值
```php
int apc->increment ( $key )
```

##### 参数
*无*

- - - -

### 减小一项缓存的值
```php
int apc->decrement( $key )
```

##### 参数
*无*

