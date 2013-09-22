Escape
======

转义字符串中的特殊字符,以便安全的输出到网页中,支持HTML,JS,CSS,HTML属性和URL的转义

案例
----

### 转义HTML字符串
```php
echo widget()->escape('<script>alert("xss")</script>');
```

#### 运行结果
```php
'&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;'
```

### 转义Javascript字符串
```php
echo widget()->escape->js('bar&quot;; alert(&quot;Meow!&quot;); var xss=&quot;true');
```

#### 运行结果
```php
'bar\x26quot\x3B\x3B\x20alert\x28\x26quot\x3BMeow\x21\x26quot\x3B\x29\x3B\x20var\x20xss\x3D\x26quot\x3Btrue'
```

### 转义CSS字符串
```php
echo widget()->escape->css("background-image: url('http://example.com/foo.jpg?</style><script>alert(1)</script>');");
```

#### 运行结果
```php
'background\2D image\3A \20 url\28 \27 http\3A \2F \2F example\2E com\2F foo\2E jpg\3F \3C \2F style\3E \3C script\3E alert\28 1\29 \3C \2F script\3E \27 \29 \3B '
```

### 转义HTML属性字符串
```php
echo '<span title=';
echo widget()->escape->attr("faketitle onmouseover=alert(/xss/);");
echo '>hi</span>';
```

#### 运行结果
```php
'<span title=faketitle&#x20;onmouseover&#x3D;alert&#x28;&#x2F;xss&#x2F;&#x29;&#x3B;>hi</span>'
```

### 转义URL字符串
```php
echo '<a href="http://example.com/?name=';
echo widget()->escape->url("onmouseover= \"alert('zf2')");
echo '">Click here!</a>';
```

#### 运行结果
```php
'<a href="http://example.com/?name=onmouseover%3D%20%22alert%28%27zf2%27%29">Click here!</a>'
```

说明
----

Escape对象是基于[Zend\Escaper](https://github.com/zendframework/zf2/tree/master/library/Zend/Escaper)组件的字符串安全转义器.
Escape对象的用法与Zend\Escaper基本一致.关于Zend\Escaper的文档可以查看这里
http://framework.zend.com/manual/2.1/en/modules/zend.escaper.introduction.html

调用方式
--------

### 选项

*无*

### 方法

#### escape($input, $type = 'html')
转义HTML字符串

#### escape->html($input)
转义HTML字符串

#### escape->js($input)
转义Javascript字符串

#### escape->css($input)
转义CSS字符串

#### escape->attr($input)
转义HTML属性字符串

#### escape->url($input)
转义URL字符串
