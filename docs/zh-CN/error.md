Error
=====

错误处理器,可自定义异常,404和fatal错误的处理

Attach a handler to the error event

### Attach a handler to the error event
```php
\EventManager error($fn, $priority, $data)
```

### 选项

名称                | 类型             | 默认值     | 说明                   |
--------------------|------------------|------------|------------------------|
extension           | string           | .html.twig | 默认的模板扩展名       |
object              | Twig_Environment | 无         | Twig对象               |
paths               | array,string     | 无         | 模板所在的目录         |
envOptions          | array            | 见下表     | 创建Twig对象的配置选项 |

### 方法

#### error($fn)
设置自定义的错误处理器

### error->notFound($fn)
设置404的错误处理器

### error->fatal($fn)
设置Fatal错误的处理器