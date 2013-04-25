EntityManager
-------------

获取[Doctrine ORM](https://github.com/doctrine/doctrine2)的实体管理对象

案例
====

### 获取Doctrine ORM的实体管理对象
```php
$em = $widget->entityManager();

// 返回 Doctrine\ORM\EntityManager();
echo get_class($em);
```

调用方式
=======

### 选项
$config
  * cache null
  * proxyDir null
  * proxyNamespace Proxy
  * autoGenerateProxyClasses Proxy
  * entityNamespaces array()
  * useSimpleAnnotationReader false

### 方法

#### entityManager()
获取Doctrine ORM的实体管理对象
