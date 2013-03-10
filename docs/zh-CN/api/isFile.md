[isFile()](http://twinh.github.com/widget/api/isFile)
=====================================================

检查数据是否为图片,同时还可以检查图片宽度和高度是否在指定的范围内

### 
```php
bool isFile( $input [, $options ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$options** `array` 选项数组,留空表示只检查数据是否为图片
   *  **maxWidth** `int` 允许的图片最大宽度
   *  **minWidth** `int` 允许的图片最小宽度
   *  **maxHeight** `int` 允许的图片最大高度
   *  **minHeight** `int` 允许的图片最小高度

