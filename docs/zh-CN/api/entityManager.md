EntityManager
=============

获取[Doctrine ORM](https://github.com/doctrine/doctrine2)的实体管理对象

案例
----

### 获取Doctrine ORM的实体管理对象
```php
$em = widget()->entityManager();

// 返回 Doctrine\ORM\EntityManager();
echo get_class($em);
```

调用方式
--------

### 选项

| 名称          | 类型      | 默认值    | 说明                                      |
|---------------|-----------|-----------|-------------------------------------------|
| config        | array     | 见下表    | `\Doctrine\ORM\Configuration`的初始化参数 |

config选项的详细配置

| 名称                      | 类型      | 默认值    | 说明  |
|---------------------------|-----------|-----------|-------|
| cache                     | string    | null      | 无    |                                                                         |
| proxyDir                  | string    | null      |       |
| proxyNamespace            | string    | 'Proxy'   |       |
| autoGenerateProxyClasses  | bool      | null      |       |
| annotationDriverPaths     | array     | array()   |       |
| entityNamespaces          | array     | array()   |       |
| useSimpleAnnotationReader | bool      | false     |       |

### 方法

#### entityManager()
获取Doctrine ORM的实体管理对象
