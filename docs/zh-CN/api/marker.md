[marker()](http://twinh.github.com/widget/api/marker)
=====================================================

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
'<table class="table table-bordered table-hover marker-table"><thead><tr><th>Marker</th><th>Time</th><th>%</th><th>Memory Usage</th><th>%</th></tr></thead><tbody><tr><th>1</th><td>48:25.1439s</span></td><td>-</td><td>4.2MB</span></td><td>-</td></tr><tr><th>1~2</th><td>48:25.1484s<span style="color:green; font-size: 0.8em">(+0.0045s)</span></td><td>48.91%</td><td>4.2MB<span style="color:green; font-size: 0.8em">(+716B)</span></td><td>52.95%</td></tr><tr><th>2~end</th><td>48:25.1531s<span style="color:green; font-size: 0.8em">(+0.0047s)</span></td><td>51.08%</td><td>4.2MB<span style="color:green; font-size: 0.8em">(+636B)</span></td><td>47.04%</td></tr></tbody></table>'
```
