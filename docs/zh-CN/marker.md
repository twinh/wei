[marker()](http://twinh.github.io/widget/api/marker)
====================================================

创建一个标记,该标记会记录当前PHP的运行时间,内存等信息

### 
```php
bool marker( [ $name ] )
```

##### 参数
* **$name** `string` 标记的名称,留空则自动生成,从1开始自增

##### 代码范例
html:创建多个标记,并输出
```php
<?php

$widget->marker();

for ($i = 0; $i < 10000; $i++) {
    null == 0;
}

$widget->marker();

for ($i = 0; $i < 10000; $i++) {
    null === 0;
}

$widget->marker('end');

$widget->marker->display();
```
##### 运行结果
```php
'<table class="table table-bordered table-hover marker-table"><thead><tr><th>Marker</th><th>Time</th><th>%</th><th>Memory Usage</th><th>%</th></tr></thead><tbody><tr><th>1</th><td>29:42.8855s</span></td><td>-</td><td>1.39MB</span></td><td>-</td></tr><tr><th>1~2</th><td>29:42.8902s<span style="color:green; font-size: 0.8em">(+0.0047s)</span></td><td>52.22%</td><td>1.39MB<span style="color:green; font-size: 0.8em">(+716B)</span></td><td>52.95%</td></tr><tr><th>2~end</th><td>29:42.8945s<span style="color:green; font-size: 0.8em">(+0.0043s)</span></td><td>47.77%</td><td>1.39MB<span style="color:green; font-size: 0.8em">(+636B)</span></td><td>47.04%</td></tr></tbody></table>'
```
