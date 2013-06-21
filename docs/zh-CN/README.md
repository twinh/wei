欢迎使用Widget文档
==================
目录
------------
1. [Widget微框架简介](../../README.md)
2. 配置

API 参考目录
------------

#### HTTP请求
* [request](request.md) - 管理HTTP请求数据($_REQUEST)
* [cookie](cookie.md) - 写入,读取和删除cookie($_COOKIE)
* [post](post.md)  - 获取一项HTTP POST请求参数($_POST)的值
* [query](query.md) - 获取一项HTTP GET查询参数($_GET)的值
* [server](server.md) - 获取一项服务器和执行环境信息($_SERVER)的值
* [session](session.md) - 获取一项会话($_SESSION)的值
* [ua](ua.md) - 检测客户端浏览器,操作系统和设备是否为指定的名称和版本
* [upload](upload.md) - 保存客户端上传的文件到指定目录

#### HTTP响应
* [response](response.md) - 发送HTTP响应头和内容
* [download](download.md) - 发送下载文件的HTTP响应
* [flush](flush.md) - 关闭缓冲区,让后台脚本实时输出内容到浏览器
* [header](header.md) - 设置和获取HTTP响应头
* [json](json.md) - 输出JSON或JSONP格式的数据到浏览器
* [redirect](redirect.md) - 跳转到指定地址

#### [缓存](cache-section.md)
* [apc](apc.md) - APC缓存
* [couchbase](couchbase.md) - Couchbase缓存
* [dbCache](dbCache.md) - 数据库缓存
* [fileCache](fileCache.md) - 文件缓存
* [memcache](memcache.md) - Memcachce缓存
* [memcached](memcached.md) - Memcached缓存
* [redis](redis.md) - Redis缓存
* [cache](cache.md) - 通用缓存
* [bicache](bicache.md) - 二级缓存

#### 数据库
* [db](db.md) - 数据库操作微件,支持基本的增删查改(CRUD)和流行的Active Record模式等数据库操作
* [queryBuilder](queryBuilder.md) - 简洁高效的SQL查询构建器
* [dbal](dbal.md) - 获取[Doctrine DBAL](https://github.com/doctrine/dbal)的Connection对象
* [entityManager](entityManager.md) - 获取[Doctrine ORM](https://github.com/doctrine/doctrine2)的EntityManager对象

#### 验证器
* [validate](validate.md) - 检查数组或对象中每一个元素是否能通过指定规则的验证(类似[jQuery Validation](http://bassistance.de/jquery-plugins/jquery-plugin-validation/)插件)
* [is](is.md) - 验证管理器,用于检查数据是否能通过指定规则,或指定回调结构的验证

数据类型及组成
* [isAlnum](isAlnum.md) - 检查数据是否只由字母(a-z)和数字(0-9)组成
* [isAlpha](isAlpha.md) - 检查数据是否只由字母(a-z)组成
* [isBlank](isBlank.md) - 检查数据是否为空(不允许空格)
* [isDecimal](isDecimal.md) - 检查数据是否为小数
* [isDigit](isDigit.md) - 检查数据是否只由数字组成
* [isDivisibleBy](isDivisibleBy.md) - 检查数据是否能被指定的除数整除
* [isDoubleByte](isDoubleByte.md) - 检查数据是否只由双字节字符组成
* [isEmpty](isEmpty.md) - 检查数据是否为空(允许空格)
* [isEndsWith](isEndsWith.md) - 检查数据是否以指定字符串结尾
* [isEquals](isEquals.md) - 检查数据是否与指定数据相等
* [isIn](isIn.md) - 检查数据是否在指定的数组中
* [isLowercase](isLowercase.md) - 检查数据是否为小写
* [isNull](isNull.md) - 检查数据是否为null
* [isNumber](isNumber.md) - 检查数据是否为有效数字
* [isRegex](isRegex.md) - 检查数据是否匹配指定的正则表达式
* [isRequired](isRequired.md) - 检查数据是否为空(用于组合验证,如果允许为空且数据为空,则不对数据进行剩余规则的校验)
* [isStartsWith](isStartsWith.md) - 检查数据是否以指定字符串开头
* [isType](isType.md) - 检查数据是否为指定的类型
* [isUppercase](isUppercase.md) - 检查数据是否为大写

长度大小
* [isBetween](isBetween.md) - 检查数据是否在指定的两个值之间
* [isLength](isLength.md) - 检查数据是否为指定的长度,或在指定的长度范围内
* [isMax](isMax.md) - 检查数据是否小于等于指定的值
* [isMaxLength](isMaxLength.md) - 检查数据是否小于等于指定长度
* [isMin](isMin.md) - 检查数据是否大于等于指定的值
* [isMinLength](isMinLength.md) - 检查数据是否大于等于指定长度

日期和时间
* [isDate](isDate.md) - 检查数据是否为合法的日期
* [isDateTime](isDateTime.md) - 检查数据是否为合法的日期时间
* [isTime](isTime) - 检查数据是否为合法的时间

文件目录
* [isDir](isDir.md)－ 检查数据是否为存在的目录
* [isExists](isExists.md) - 检查数据是否为存在的文件或目录
* [isFile](isFile.md) - 检查数据是否为合法的文件
* [isImage](isImage.md) - 检查数据是否为合法的图片

网络
* [isEmail](isEmail.md) - 检查数据是否为有效的邮箱地址
* [isIp](isIp.md) - 检查数据是否为有效的IP地址
* [isTld](isTld.md) - 检查数据是否为存在的顶级域名
* [isUrl](isUrl.md) - 检查数据是否为有效的URL地址
* [isUuid](isUuid.md) - 检查数据是否为有效的UUID

区域:所有
* [isCreditCard](isCreditCard.md) - 检查数据是否为合法的信用卡号码

区域:中国
* [isChinese](isChinese.md) - 检查数据是否只由中文组成
* [isIdCardCn](isIdCardCn.md) - 检查数据是否为有效的中国身份证
* [isIdCardHk](isIdCardHk.md) - 检查数据是否为有效的香港身份证
* [isIdCardMo](isIdCardMo.md) - 检查数据是否为有效的澳门身份证
* [isIdCardTw](isIdCardTw.md) - 检查数据是否为有效的台湾身份证
* [isPhoneCn](isPhoneCn.md) - 检查数据是否为有效的电话号码
* [isPostcodeCn](isPostcodeCn.md) - 检查数据是否为有效的邮政编码
* [isQQ](isQQ.md) - 检查数据是否为有效的QQ号码
* [isMobileCn](isMobileCn.md) - 检查数据是否为有效的手机号码

分组
* [isAllOf](isAllOf.md) - 检查数据是否通过所有的规则校验
* [isNoneOf](isNoneOf.md) - 检查数据是否不符合所有指定的规则
* [isOneOf](isOneOf.md) - 检查数据是否满足指定规则中的任何一条
* [isSomeOf](isSomeOf.md) - 检查数据是否通过指定数量规则的验证

数据库
* [isEntityExists](isEntityExists.md) - 检查Doctrine ORM实体是否存在
* [isRecordExists](isRecordExists.md) - 检查数据表是否存在指定的记录

其他
* [isAll](isAll.md) - 检查集合里的每一项是否符合指定的规则
* [isCallback](isCallback.md) - 检查数据是否通过指定回调方法验证
* [isColor](isColor.md) - 检查数据是否为有效的十六进制颜色

#### 视图
* [escape](escape.md) - 转义字符串中的特殊字符,以便安全的输出到网页中,支持HTML,JS,CSS,HTML属性和URL的转义
* [smarty](smarty.md) - 渲染smarty模板
* [twig](twig.md) - 渲染Twig模板
* [view](view.md) - 渲染指定名称的模板

#### 事件
* [event](event.md) - 事件管理器,支持绑定,触发,移除事件和命名空间等特性.

#### 错误处理
* [error](error.md) - 提供简单友好的错误界面
* [phpError](phpError.md) - 启用[PHPError](http://phperror.net/)的错误视图

#### 工具
* [arr](attr.md) - 数组工具微件,提供一些实用的数组操作方法
* [env](env.md) - 环境检测及根据不同环境加载不同的配置文件
* [logger](logger.md) - 记录日志
* [monolog](monolog.md) - 获取[Monolog](https://github.com/Seldaek/monolog)对象或记录一条日志
* [pinyin](pinyin.md) - 将中文转换为拼音字母
* [uuid](uuid.md) - 生成一个随机的UUID
* [website](website.md) - 设置和获取网站自定义网站配置信息