isEntityExists
==============

检查Doctrine ORM实体是否存在

案例
----

### 检查主键为1的用户是否存在,和检查name为test的用户是否存在
```php
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
widget()->config('db', array(
    'driver' => 'pdo_sqlite',
    'memory' => true
));

// Set configuration for entityManager widget
widget()->config('entityManager', array(
    'config' => array(
        'proxyDir' => './',
        'proxyNamespace' => 'Proxy',
        'useSimpleAnnotationReader' => true,
        'annotationDriverPaths' => array('./')
    )
));

/* @var $em \Doctrine\ORM\EntityManager */
$em = widget()->entityManager();

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
if (widget()->isEntityExists('1', 'User')) {
    echo 'Yes';
} else {
    echo 'No';
}

// Check if the name=test user exists
if (widget()->isEntityExists('test', 'User', 'name')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
Array
(
    [0] => CREATE TABLE users (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(256) NOT NULL, PRIMARY KEY(id))
)
'Yes'
'Yes'
```

调用方式
--------

### 选项

| 名称                | 类型    | 默认值                 | 说明                         |
|---------------------|---------|------------------------|------------------------------|
| entityClass         | string  | 无                     | 实体的类名                   |
| field               | string  | 无                     | 指定的字段名称,留空表示主键  |
| notFoundMessage     | string  | %name%不存在           | -                            |
| negativeMessage     | string  | %name%已存在           | -                            |

### 方法

#### isEntityExists($input, $entityClass, $field = 'id')
检查Doctrine ORM实体是否存在
