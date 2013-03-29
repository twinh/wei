欢迎使用Widget文档
==================

### Widget微框架简介
* [概览(README.md)](../../README.md)

### 参考目录

#### 缓存
* [Apc](api/apc.md)
* [DbCache](api/dbCache.md)
* [File](api/file.md)
* [Memcache](api/memcache.md)
* [Memcached](api/memcached.md)
* [Redis](api/redis.md)
* [Cache](api/cache.md) 
* [Bicache](api/bicache.md)

#### 事件
* [EventManager](api/eventManager.md)
* [Off](api/off.md)
* [On](api/on.md)
* [Trigger](api/trigger.md)

#### HTTP请求

#### HTTP响应

#### 验证器
数据类型及组成
* [isAlnum](api/isAlnum.md) - 检查数据是否只由字母(a-z)和数字(0-9)组成
* [isAlpha](api/isAlpha.md) - 检查数据是否只由字母(a-z)组成
* [isBlank](api/isBlank.md) - 检查数据是否为空(不允许空格)
* [isDecimal](api/isDecimal.md) - 检查数据是否为小数
* [isDigit](api/isDigit.md) - 检查数据是否只由数字组成
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

长度大小
* [isLength](api/isLength.md) - 检查数据是否为指定的长度,或在指定的长度范围内
* [isMax](api/isMax.md) - 检查数据是否小于等于指定的值
* [isMaxLength](api/isMaxlength.md) - 检查数据是否小于等于指定长度
* [isMin](api/isMin.md) - 检查数据是否大于等于指定的值
* [isMinLength](api/isMinlength.md) - 检查数据是否大于等于指定长度
* [isRange](api/isRange.md) - 检查数据是否在指定的两个值之间

日期和时间
* [isDate](api/isdate.md) - 检查数据是否为合法的日期
* [isDateTime](api/isdatetime.md) - 检查数据是否为合法的日期时间
* [isTime](api/istime) - 检查数据是否为合法的时间

文件目录
* [isDir](api/isDir.md)－ 检查数据是否为存在的目录
* [isExists](api/isExists.md) - 检查数据是否为存在的文件或目录
* [isFile](api/isFile.md) - 检查数据是否为合法的文件
* [isImage](api/isImage.md) - 检查数据是否为合法的图片

网络
* [isEmail](api/isEmail.md) - 检查数据是否为有效的邮箱地址
* [isIp](api/isIp.md) - 检查数据是否为有效的IP地址
* [isTld](api/isTld.md) - 检查数据是否为存在的顶级域名
* [isUrl](api/isUrl.md) - 检查数据是否为有效的URL地址
* [isUuid](api/isUuid.md) - 检查数据是否为有效的UUID

区域:所有
* [isCreditCard](api/isCreditcard.md) - 检查数据是否为合法的信用卡号码

区域:中国
* [isChinese](api/isChinese.md) - 检查数据是否只由中文组成
* [isIdCardCn](api/isIdCardCn.md) - 检查数据是否为有效的中国身份证
* [isIdCardHk](api/isIdCardHk.md) - 检查数据是否为有效的香港身份证
* [isIdCardMo](api/isIdCardMo.md) - 检查数据是否为有效的澳门身份证
* [isIdCardTw](api/isIdCardTw.md) - 检查数据是否为有效的台湾身份证
* [isPhoneCn](api/isPhoneCn.md) - 检查数据是否为有效的电话号码
* [isPostcode](api/isPostcode.md) - 检查数据是否为有效的邮政编码
* [isQQ](api/isQQ.md) - 检查数据是否为有效的QQ号码
* [isMobileCn](api/isMobileCn.md) - 检查数据是否为有效的手机号码

分组
* [isAllOf](api/isAllof.md) - 检查数据是否通过所有的规则校验
* [isNoneOf](api/isNoneof.md) - 检查数据是否不符合所有指定的规则
* [isOneOf](api/isOneof.md) - 检查数据是否满足指定规则中的任何一条
* [isSomeOf](api/isSomeof.md) - 检查数据是否通过指定数量规则的验证

第三方集成
* [isEntityExists](api/isEntityexists.md) - 检查Doctrine ORM实体是否存在

其他
* [isAll](api/isAll.md) - 检查集合里的每一项是否符合指定的规则
* [isCallback](api/isCallback.md) - 检查数据是否通过指定回调方法验证
* [isDivisibleBy](api/isDivisibleby.md) - 检查数据是否能被指定的除数整除

#### 工具
