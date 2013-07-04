欢迎使用Widget文档
==================

文档目录
------------

1. [简介](book/introduction.md)
2. [安装](book/installation.md)
3. [配置](book/configuration.md)
4. [缓存](book/cache.md)
5. [数据库](api/db.md)
6. [数据校验](api/validate.md)
7. [接口调用](api/call.md)

API参考目录
-----------

#### Widget管理器

* [widget](api/widget.md) - 微件管理器,用于获取微件对象,设置配置等

#### [缓存](book/cache.md)
* [apc](api/apc.md) - APC缓存
* [arrayCache](api/arrayCache.md) - PHP数组缓存
* [couchbase](api/couchbase.md) - Couchbase缓存
* [dbCache](api/dbCache.md) - 数据库缓存
* [fileCache](api/fileCache.md) - 文件缓存
* [memcache](api/memcache.md) - Memcachce缓存
* [memcached](api/memcached.md) - Memcached缓存
* [redis](api/redis.md) - Redis缓存
* [cache](api/cache.md) - 通用缓存
* [bicache](api/bicache.md) - 二级缓存

#### 数据库
* [db](api/db.md) - 数据库操作微件,支持基本的增删查改(CRUD)和流行的Active Record模式等数据库操作
* [queryBuilder](api/queryBuilder.md) - 简洁高效的SQL查询构建器

#### 验证器
* [validate](api/validate.md) - 检查数组或对象中每一个元素是否能通过指定规则的验证(类似[jQuery Validation](http://bassistance.de/jquery-plugins/jquery-plugin-validation/)插件)
* [is](api/is.md) - 验证管理器,用于检查数据是否能通过指定规则,或指定回调结构的验证

##### 数据类型及组成
* [isAlnum](api/isAlnum.md) - 检查数据是否只由字母(a-z)和数字(0-9)组成
* [isAlpha](api/isAlpha.md) - 检查数据是否只由字母(a-z)组成
* [isBlank](api/isBlank.md) - 检查数据是否为空(不允许空格)
* [isDecimal](api/isDecimal.md) - 检查数据是否为小数
* [isDigit](api/isDigit.md) - 检查数据是否只由数字组成
* [isDivisibleBy](api/isDivisibleBy.md) - 检查数据是否能被指定的除数整除
* [isDoubleByte](api/isDoubleByte.md) - 检查数据是否只由双字节字符组成
* [isEmpty](api/isEmpty.md) - 检查数据是否为空(允许空格)
* [isEndsWith](api/isEndsWith.md) - 检查数据是否以指定字符串结尾
* [isEquals](api/isEquals.md) - 检查数据是否与指定数据相等
* [isIn](api/isIn.md) - 检查数据是否在指定的数组中
* [isLowercase](api/isLowercase.md) - 检查数据是否为小写
* [isNull](api/isNull.md) - 检查数据是否为null
* [isNumber](api/isNumber.md) - 检查数据是否为有效数字
* [isRegex](api/isRegex.md) - 检查数据是否匹配指定的正则表达式
* [isRequired](api/isRequired.md) - 检查数据是否为空(用于组合验证,如果允许为空且数据为空,则不对数据进行剩余规则的校验)
* [isStartsWith](api/isStartsWith.md) - 检查数据是否以指定字符串开头
* [isType](api/isType.md) - 检查数据是否为指定的类型
* [isUppercase](api/isUppercase.md) - 检查数据是否为大写

##### 长度大小
* [isBetween](api/isBetween.md) - 检查数据是否在指定的两个值之间
* [isLength](api/isLength.md) - 检查数据是否为指定的长度,或在指定的长度范围内
* [isMax](api/isMax.md) - 检查数据是否小于等于指定的值
* [isMaxLength](api/isMaxLength.md) - 检查数据是否小于等于指定长度
* [isMin](api/isMin.md) - 检查数据是否大于等于指定的值
* [isMinLength](api/isMinLength.md) - 检查数据是否大于等于指定长度

##### 日期和时间
* [isDate](api/isDate.md) - 检查数据是否为合法的日期
* [isDateTime](api/isDateTime.md) - 检查数据是否为合法的日期时间
* [isTime](api/isTime) - 检查数据是否为合法的时间

##### 文件目录
* [isDir](api/isDir.md)－ 检查数据是否为存在的目录
* [isExists](api/isExists.md) - 检查数据是否为存在的文件或目录
* [isFile](api/isFile.md) - 检查数据是否为合法的文件
* [isImage](api/isImage.md) - 检查数据是否为合法的图片

##### 网络
* [isEmail](api/isEmail.md) - 检查数据是否为有效的邮箱地址
* [isIp](api/isIp.md) - 检查数据是否为有效的IP地址
* [isTld](api/isTld.md) - 检查数据是否为存在的顶级域名
* [isUrl](api/isUrl.md) - 检查数据是否为有效的URL地址
* [isUuid](api/isUuid.md) - 检查数据是否为有效的UUID

##### 区域:所有
* [isCreditCard](api/isCreditCard.md) - 检查数据是否为合法的信用卡号码

##### 区域:中国
* [isChinese](api/isChinese.md) - 检查数据是否只由中文组成
* [isIdCardCn](api/isIdCardCn.md) - 检查数据是否为有效的中国身份证
* [isIdCardHk](api/isIdCardHk.md) - 检查数据是否为有效的香港身份证
* [isIdCardMo](api/isIdCardMo.md) - 检查数据是否为有效的澳门身份证
* [isIdCardTw](api/isIdCardTw.md) - 检查数据是否为有效的台湾身份证
* [isPhoneCn](api/isPhoneCn.md) - 检查数据是否为有效的电话号码
* [isPostcodeCn](api/isPostcodeCn.md) - 检查数据是否为有效的邮政编码
* [isQQ](api/isQQ.md) - 检查数据是否为有效的QQ号码
* [isMobileCn](api/isMobileCn.md) - 检查数据是否为有效的手机号码

##### 分组
* [isAllOf](api/isAllOf.md) - 检查数据是否通过所有的规则校验
* [isNoneOf](api/isNoneOf.md) - 检查数据是否不符合所有指定的规则
* [isOneOf](api/isOneOf.md) - 检查数据是否满足指定规则中的任何一条
* [isSomeOf](api/isSomeOf.md) - 检查数据是否通过指定数量规则的验证

##### 数据库
* [isEntityExists](api/isEntityExists.md) - 检查Doctrine ORM实体是否存在
* [isRecordExists](api/isRecordExists.md) - 检查数据表是否存在指定的记录

##### 其他
* [isAll](api/isAll.md) - 检查集合里的每一项是否符合指定的规则
* [isCallback](api/isCallback.md) - 检查数据是否通过指定回调方法验证
* [isColor](api/isColor.md) - 检查数据是否为有效的十六进制颜色

#### HTTP请求
* [request](api/request.md) - 管理HTTP请求数据($_REQUEST)
* [cookie](api/cookie.md) - 写入,读取和删除cookie($_COOKIE)
* [post](api/post.md)  - 获取一项HTTP POST请求参数($_POST)的值
* [query](api/query.md) - 获取一项HTTP GET查询参数($_GET)的值
* [server](api/server.md) - 获取一项服务器和执行环境信息($_SERVER)的值
* [session](api/session.md) - 获取一项会话($_SESSION)的值
* [ua](api/ua.md) - 检测客户端浏览器,操作系统和设备是否为指定的名称和版本
* [upload](api/upload.md) - 保存客户端上传的文件到指定目录

#### HTTP响应
* [response](api/response.md) - 发送HTTP响应头和内容
* [download](api/download.md) - 发送下载文件的HTTP响应
* [flush](api/flush.md) - 关闭缓冲区,让后台脚本实时输出内容到浏览器
* [header](api/header.md) - 设置和获取HTTP响应头
* [json](api/json.md) - 输出JSON或JSONP格式的数据到浏览器
* [redirect](api/redirect.md) - 跳转到指定地址

#### 视图
* [escape](api/escape.md) - 转义字符串中的特殊字符,以便安全的输出到网页中,支持HTML,JS,CSS,HTML属性和URL的转义
* [view](api/view.md) - 渲染指定名称的PHP模板

#### 工具
* [arr](api/attr.md) - 数组工具微件,提供一些实用的数组操作方法
* [env](api/env.md) - 环境检测及根据不同环境加载不同的配置文件
* [call](api/call.md) - 象jQuery Ajax一样调用您的接口
* [error](api/error.md) - 提供简洁友好的错误界面
* [event](api/event.md) - 事件管理器,支持绑定,触发,移除事件和命名空间等特性.
* [logger](api/logger.md) - 记录日志
* [pinyin](api/pinyin.md) - 将中文转换为拼音字母
* [uuid](api/uuid.md) - 生成一个随机的UUID
* [website](api/website.md) - 设置和获取网站自定义网站配置信息

#### 第三方

##### 数据库
* [dbal](api/dbal.md) - 获取[Doctrine DBAL](https://github.com/doctrine/dbal)的Connection对象
* [entityManager](api/entityManager.md) - 获取[Doctrine ORM](https://github.com/doctrine/doctrine2)的EntityManager对象

##### 视图
* [smarty](api/smarty.md) - 渲染smarty模板
* [twig](api/twig.md) - 渲染Twig模板

##### 其他
* [monolog](api/monolog.md) - 获取[Monolog](https://github.com/Seldaek/monolog)对象或记录一条日志
* [phpError](api/phpError.md) - 启用[PHPError](http://phperror.net/)的错误视图