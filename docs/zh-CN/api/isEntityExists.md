[isEntityExists()](http://twinh.github.com/widget/api/isEntityExists)
=====================================================================

检查Doctrine ORM实体是否存在

### 
```php
bool isEntityExists( $input [, $entityClass [, $field ] ] )
```

##### 参数
* **$input** `mixed` 待验证的数据,一般为主键的值
* **$entityClass** `string` 实体的类名
* **$field** `string` 指定的字段名称,留空表示主键

##### 范例
检查主键为1的用户是否存在,和检查name为test的用户是否存在
```php
<?php

/** @Entity @Table(name="users") */
class User
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=50) */
    private $name;
    
    /** @Column(type="string", length=256) */
    private $address;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }
}

// Set configuraion for db widget
$widget->config('db', array(
    'params' => array(
        'driver' => 'pdo_sqlite',
        'memory' => true
    ),
));

// Set configuration for entityManager widget
$widget->config('entityManager', array(
    'config' => array(
        'proxyDir' => './',
        'proxyNamespace' => 'Proxy',
        'useSimpleAnnotationReader' => true,
        'annotationDriverPaths' => array('./')
    )
));

/* @var $em \Doctrine\ORM\EntityManager */
$em = $widget->entityManager();

$tool = new Doctrine\ORM\Tools\SchemaTool($em);

$metadata = $em->getClassMetadata('User');

print_r($tool->getCreateSchemaSql(array($metadata)));

// Create table from User entity
$tool->createSchema(array($metadata));

// Insert some test data
$user1 = new User();
$user1->setName('twin');
$user1->setAddress('home');
$em->persist($user1);

$user2 = new User();
$user2->setName('test');
$user2->setAddress('office');
$em->persist($user2);

$em->flush();

// Check if the id=1 user exists
if ($widget->isEntityExists('1', 'User')) {
    echo 'Yes';
} else {
    echo 'No';
}

// Check if the name=test user exists
if ($widget->isEntityExists('test', 'User', 'name')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Array
(
    [0] => CREATE TABLE users (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(256) NOT NULL, PRIMARY KEY(id))
)
YesYes'
```
