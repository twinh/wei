## 0.9.24 (2020-07-23)


### Bug Fixes

* schema nullable 设置无效 ([fb6781b](https://github.com/twinh/wei/commit/fb6781b492699e06f7c0d7ce33b1ca070d4491c5))
* 命名空间不正确 ([0a5f57a](https://github.com/twinh/wei/commit/0a5f57abc34b102ce42d4752cac89a2a585b7396))
* 改用 rawurlencode 来转换 cookie ([3f1070c](https://github.com/twinh/wei/commit/3f1070cd77512f3300a945b9e2dbc2dd9fe529c7))
* 非数字值 incr 出错  ErrorException : A non-numeric value encountered ([a91ee96](https://github.com/twinh/wei/commit/a91ee96a55a71a47cb38862f963fe82af00c40a8))
* Request: 解决 &offsetGet 导致 if (wei()->request['notExists']) 会在 request 内部的 data 产生额外键 notExists = null 的问题

### Features

* ClassMap 加入 wei 中 ([cea4791](https://github.com/twinh/wei/commit/cea4791a29a425abebfe6242d188fb844017b613))
* Migration 加入 wei 中 ([aab0acb](https://github.com/twinh/wei/commit/aab0acb4dba4abc1f5895f6d6201f7bfffebc468))
* Request 可以读取到提交的 JSON 数据 ([0cefe24](https://github.com/twinh/wei/commit/0cefe2474ec83a8125870db4265cc9e0b8ef029e))
* Request 增加 isFormat 判断请求格式 ([5b8b57e](https://github.com/twinh/wei/commit/5b8b57ed7f4d82fbdb0a58a9c7d8ca1ace630754))
* ServiceTrait 加入 wei 中 ([6106fd9](https://github.com/twinh/wei/commit/6106fd98b520572c65f3aeedd7da57ff1a0f22ec))
* Time 服务加入 wei 中 ([26088e3](https://github.com/twinh/wei/commit/26088e3fc051012ced9d4e4a835758ecac6d45eb))
* V 服务加入 wei 中 ([ed8332e](https://github.com/twinh/wei/commit/ed8332eb351765ee7b58e7d0cf78bd780c505bc7))
* **error:** 支持返回 JSON 结果 ([6be31d3](https://github.com/twinh/wei/commit/6be31d39fe87b2558999f4daea012cb7b6da9e69))
* **error:** 获取错误信息时，尝试将异常错误码转换为 HTTP 状态文本；错误视图可以使用 $title, $message 参数 ([3d0ff81](https://github.com/twinh/wei/commit/3d0ff81dd4bcab1d248dddd19f1d505e43d47619))
* **upload:** 增加 `getUrl` 方法，返回上传文件的地址 ([7638e8c](https://github.com/twinh/wei/commit/7638e8ca6a15092604020a6720e6143ea8877c8f))
* batchInsert 也自动转换 false 值为 0 ([f7ebe4d](https://github.com/twinh/wei/commit/f7ebe4d3d9ddafb4de551fcebaedf326f852626b))
* schema 增加 defaultNullable 选项，控制是否默认为 null ([4a10c4b](https://github.com/twinh/wei/commit/4a10c4b50d389b7353a9124d72e0fc7116575cd0))
* 初步增加静态调用 ([756003f](https://github.com/twinh/wei/commit/756003f206dfcbf699fd20faa28ad0b4e5f0b44d))
* 动态调用也支持指定 $createNewInstance=true 来创建新的对象 ([540c902](https://github.com/twinh/wei/commit/540c9021d677cb25728e592cb1e1534289bc4773))
* 增加 dropIndex 支持 ([53a052c](https://github.com/twinh/wei/commit/53a052ce380c4efe5bcbc179cf1bc7930dae5737))
* 增加 to 方法 ([7b84508](https://github.com/twinh/wei/commit/7b845083ee892cbe0043cd544abe5d31a28693a4))
* 支持使用 URL 参数 r 作为路由 ([7de4f06](https://github.com/twinh/wei/commit/7de4f0606cc3dce5b2b445a7e3a043360bed3675))
* 自动转换 false 值为 0，避免 MySQL 默认的 sql mode "STRICT_TRANS_TABLES" 提示"Incorrect integer value: '' for column ..." ([ef91135](https://github.com/twinh/wei/commit/ef91135ff8bdd923c8c98504718c0bb48245d62b))
* Block: 增加 js,css 快捷方法
* isCallback: 回调函数除了闭包, 还允许传入字符串,数字
* Record: reload, saveColl 方法使用 $this->primaryKey 代替 id 字符串
* Db: 废弃 insertBatch 方法，改名为 batchInsert
* Wei: 移除 PHP 5.3 的支持，要求 PHP 7.2+

## 0.9.23 (2017-03-20)

* Schema: 增加MySQL表操作服务,支持创建修改数据表,字段等
* Lock: 增加expire选项,默认为30秒,用于避免PHP出错后没有解锁导致死锁
* Db: 增加transactional方法,通过回调的方式调用事务
* Record: 增加forUpdate,forShare,lock方法用于事务中的锁
* Db: insertBatch增加extra参数,可用于在生成的语句后面增加"ON DUPLICATE KEY UPDATE"字句
* Http: 网络请求失败时,抛出的异常code加2000(如404则异常code为2404),避免被error服务认为是当前页面404
* App: 自定义控制器格式中的命名空间,要求大写形式

## 0.9.22 (2016-11-22)

* StatsD: 增加StatsD服务,用于发送统计数据到https://github.com/etsy/statsd
* NearCache: 增加NearCache服务,可用于缓存远程数据到本地缓存中,减少远程调用
* Memcached: 兼容memcached 3.0 getMulti方法只支持两个参数的情况
* Env: 允许通过本地的.env.php返回值获取环境名称
* Error: 增加autoExit选项,在命令行下运行失败时,会调用exit返回错误码,以便外部程序判断运行结果
* Record: 修复page传入过大值后,offset溢出变负数的问题 #184

## 0.9.21 (2016-08-04)

* App: 允许controllerFormat为空,使getControllerClasses方法返回正确的类名
* App: 增加setControllerFormat方法
* Event: 增加loadEvent回调,可用于加载或按需绑定事件
* Event: 增加getCurName方法,获取当前事件名称
* Env: 增加loadConfigDir方法,用于加载目录下的配置文件
* Redis: 解决使用redis扩展2.2.8,set方法传expire为0时,key会马上失效的问题
* View: 增加parseResource回调,可用于外部解析自定义视图路径
* Ret: 增加Ret服务和trait,用于统一构造操作结果,如操作成功/失败
* Asset: 增加fallback方法,用于生成当CDN加载失败时,回退到本地的素材地址
* BaseController: middleware方法改为public,允许外部(如通过事件)设置middleware
* WeChatApp: 初步增加转发多客服功能
* Record: 修复record有默认数据时,filter返回数据和默认数据混淆在一起的问题
* Session: start方法支持session关闭后重新启动
* Request: setServer,setQuery,setPost支持数组参数
* Request: 增加getReferer快捷方法
* Request: 修复HTTPS端口错误
* Logger: 记录日志时,$context参数允许非数组
* WeChatApp: 增加加密支持
* Http: 增加文件上传支持
* Router: 修复设置单复数转换无效
* IsStartsWith,IsEndsWith: 修复查找的对象是多个时,生成的正则不正确
* Cache: 设置缓存失败时,不抛出异常,以免程序中止.设置失败时,需由set处理或调用方处理
* Record: update方法增加数组参数支持
* Record: 查询时如果没有设置FROM的数据表,使用当前数据表作为FROM的数据表
* Bicache: 修复命名空间重复,因为master,slave已有命名空间
* Record: 修复新对象中,toArray指定了字段却没有返回的问题
* Record: 修复PostgreSQL不允许int id传入字符串导致测试错误
* Record: SQL缓存key加上数据库名称,避免分/多库存在相同查询导致key冲突
* Wei: 移除自动注入namespace功能,原因一是目前只有cache,session,app会用到,二是不便于namespace共享和切换
* Router: 路由解析出的参数都转换为驼峰形式
* Logger: 增加formatParams方法,方便子类调用
* Http: getCurlInfo增加option参数
* App: middleware支持返回字符串或数组,与action行为类似
* Http: 增加重试支持
* Http: 修复http服务未返回数组时,调用offsetExists出现array_key_exists() expects parameter 2 to be array, null given的问题
* Error: 增加简单的cli错误视图
* Record: 修复indexBy重复调用出错

## 0.9.20 (2015-06-18)

* Event: 移除$wei作为事件的首个参数 #183
* Event: 增加until方法,当获取到首个非null返回就停止执行剩下的事件 #183

## 0.9.19 (2015-05-25)

* Record: setData可以传入数组或ArrayAccess refs 39588ce285624ffca23138a6b9aacf1ce3810fcc
* Env: env支持多个服务器都设置为相同的环境名称 refs e96e7ff63919c80425d5b7733a5468f74f107cf0
* TagCache: 增加标签缓存服务 #180
* Record: 增加缓存和标签缓存支持,增加了cache, tags, setCacheKey, clearTagCache等缓存方法 #180
* Event: 增加事件功能 #181

## 0.9.18 (2015-04-09)

* App: action的命名改为以Action结尾,以便不和关键字new, default,list冲突 refs be6f6f1
* App: forward方法支持设置请求参数 refs 681f475
* App: 修复dispatch接收到forward通知后,未返回response的问题 refs 96721a8
* App: 控制器增加before和after方法 refs fed3425
* Router: 修复路由解析出的控制器缺少根控制器的问题 refs 5940754
* Cache: 增加getResultCode,用于缓存失败时,展示错误码 refs 98399d5
* Block: 增加block服务,用于视图模板继承 refs decae8d
* Db: 增加useDb,用来切换数据库 refs 42dd43f
* View: 修复在view中再次调用render方法会导致布局渲染错误的问题 refs 08719ee
* Record: 增加fillable和guarded,默认除了id,其他字段都可以接收外部数据(感谢Laravel) refs aa37be6
* Record: 移除通过属性设置回调的功能 refs a6cec67
* Record: 开放setData方法,允许设置任意字段的数据,不受fillable和guarded影响 refs cf6dd98

## 0.9.17 (2015-03-27)

* Request: 修复当URL地址包含端口时,getHost返回值错误, refs b5478fc
* Url: 修复生成查询地址错误, refs d7cd2aa
* Request: 控制返回的HTTP方法为大写, refs b70df92
* Test: 升级PHPUnit到4.5, refs e124661
* Router: 重构路由器服务,支持解析Path info为多组controller和action参数 #178
* App: 重构应用服务,支持传入多组controller和action参数,并查找存在的一组作为当前应用运行 #178
* Middleware: App服务支持中间件,可以用来控制用户登录,限制IP,CSRF保护等 #178
* 更新演示代码, refs 16fc1ab

## 0.9.16 (2015-01-29)

* Logger: Changed property "name" to "namespace", refs f1ac3e0
* Cache: Changed property "prefix" to "namespace", refs 1d38b8d
* Wei: Changed property "name" to "namespace", and injects namespace to services when instanced, refs 41c7b24
* App: Added namespace property, refs 487cc9b
* Url: Added append method to generate URL without base URL, refs c7ce111
* Request: Added acceptJson method and overwriteFormat property to detect JSON request, refs b01182c
* View: Uses property instead of local variables to avoid variables overwriting, refs ce37fdf
* View: Throws 500 exception instead of 404 when template not found, refs b80f222
* IsMobileCn: Allows Chinese mobile number starts with 17, refs 0a1217d
* Url: Added sprintf support for URL generating, refs ac60d9f
* PhpFileCache: added PhpFileCache service, refs 082275b

## 0.9.15 (2014-12-07)

* Router: Drop REST router support, refs 56da468
* Request: Added getQueries method, refs 82ac19f
* Wei: Added setServices method, fixed preload services missing issue, refs 584b3b1
* Record: Removed order by in count sql, refs 09798cf
* Record: Speed up count query, refs 83630b9
* Ua: Added isWeChat method to check if in WeChat app, refs 699995f
* Record: Fixed indexBy error when fetched data is empty, refs 0fc9b37
* Record: Added conditions parameter for fetch and fetchAll methods, refs c5fb556
* Record: Added parameter for SQL COUNT function argument, refs 08302b8
* Lock: Releases locks and exist when catch signal in CLI, refs ab4fb34
* WeChatApp: Removed 'qrscene_' prefix in getScanSceneId method, refs e56ae98
* Asset: Added array parameter support for __invoke method, refs 39870ed
* Soap: Added soap service works like http service, refs 81798a6
* Log: Allows to log exception as message, refs ab2d33f
* WeChatApp: Returns empty string if no rule is handled, http://mp.weixin.qq.com/wiki/index.php?title=%E5%8F%91%E9%80%81%E8%A2%AB%E5%8A%A8%E5%93%8D%E5%BA%94%E6%B6%88%E6%81%AF , refs ab2d33f
* App: Force response content to be array, refs 6b54527
* Session: Added namespace support, refs 21da6ed
* Session, Cookie, Request: Fixed the error "Indirect modification of overloaded element of Wei\Session has no effect" caused by set session on non exist key, refs a6f665b
* View: Added empty view directory support, refs 506fa3d
* Asset: Allows empty base url for concatenate files, refs 89899f2
* Record: Ignores default data in saveColl, refs 14223e7
* Url: Added query method for url service to generate url with current query parameters, refs e4139d4
* App: Added getControllerAction, refs 84f89bb
* Wei: Allows non \Wei\Base object as service, refs a0f5640
* Record: Added fetchColumn method, refs 49dde48
* Record: Added countBySubQuery method for record service, refs bb5e189
* Record: Fixed undefined offset 0 when fetch empty data, refs 616bb9c

## 0.9.14 (2014-03-24)

* Use full dir in file cache service
* Added addRecordClass method for db service
* Fixed weChatApp service getKeyword method return false when event key is "CLICK"
* Added name property for service container

## 0.9.13 (2014-03-16)

* Added $returnFields parameters for toJson method
* Fixed saveColl error when record id conflict with array key
* Added setQuery, setPost methods for request service
* Removes session namespace options
* Fixed WeChat sort bug
* Added getTicket, getKeyword, getScanSceneId methods for weChatApp service
* Added scan and subscribe support for weChatApp service

## 0.9.12 (2014-02-13)

* Add "detach" support for record class, refs #164
* Add setAll and setAll method for record class, refs #168

## 0.9.11 (2014-02-05)

* Added safeUrl service, close #171
* Added defaultLayout property for view service
* Added postJson method for http service, close #172

## 0.9.10 (2014-01-27)

* Renamed "call" service to "http", refs #157
* Removed default value for HTTP service content type
* Install apcu for PHP 5.5 unit test, refs #161
* Allows ArrayAccess as record class findOrInit method parameter
* Ignores record class query condition when condition is false
* Returns current record object instead of true/false in save, destroy method
* Added isColl method for record class
* Added findById, findOneById, findOrInitById methods for record class, refs #166
* Added size method for recrod calss
* Added concat method for asset service
* Fixed missing default data when fetching non exists record
* Removes destroy recrod from collection list, refs 14fea8b
* Added sort support for record class saveColl method, refs be96a40
* Fixed saveColl variable name error, refs 4024d8b
* Drops PHP 5.3.3 support, requires at least 5.3.4, refs 05e4416e4239568ae5856783f269ca48c869aafb
* Added JSON_UNESCAPED_UNICODE support for json response, refs 68bae3eb3797e4514ffee9d66b5a167cf6eb123b
* Added afterFind callback for record , refs classb37d5040eec3601abddf25c6246fee0731473fde
* Added isLoaded method for record class, refs 2ff3790a90d475aefc191dd73fb25d0693af0f48

## 0.9.9 (2014-01-08)

* Do not output libxml error messages to screen in weChatApp service, refs #153

## 0.9.9-RC (2014-01-05)

* Added fullTable property for record class
* Fixed db test error when table name contains alias
* Merged error view file into error service
* Moved unit tests to `tests/unit` directory
* Added condition parameter for record class destroy method
* Added version parameter for asset service
* Renamed escape service to "e"
* Throws exception when autoload directory not found
* Decouple response service with escape service
* Simplified url service to generate url without router service
* Added array access for view service
* Added getMap method for config service
* Renamed view service vars property to data
* Append logger context to log message
* Added http service, which is an alias of call service
* Throws InvalidArgumentException when password service salt option is too short
* Fixed record class findOrInit method returns error isNew flag when record not found
* Fixed test error when primary key is null
* Added saveColl method for record class
* Added support for collection record to auto increment array key when key is null
* Fixed primary key not receive when primary key value is null
* Removed max-age in call service responded cookie
* Automatic decode call service responded cookie value
* Added getPostData method for weChatApp service
* Removed call service setRequestHeader method
* Removes call service deleted cookie, ref #155
* Added getPdo method for db service
* Uses full table name as db service tableFields key
* Added getTablePrefix method for db service, close #154
* Renamed db service create method to init
* Added real time indexBy support for record class, refs #158
* Added isContains validator, refs #149
* Allows url service url parameter be empty
* Added batch insert for SQLite before 3.7.11, closes #159
* Added HHVM test env and fixed some unit test error in HHVM, refs #153
* Updated phpunit version for HHVM test, refs #153
* Fixed HHVM test error "Too many arguments for pi()", refs #153
* Fixed HHVM memcache extension test error: "Unable to handle compressed values yet", refs #153
* Fixed HHVM APC incr method test error, refs #153
* Changed memcache service flag option default to 0, for zlib is not installed conditions parameter for record class by default
* Added conditions parameter for record class count method
* Removed runInSeparateProcess annotation for session test
* Uses fileCache as default cache driver, make sure it available for all unit tests
* Simplified isDateTime validator, fixed isDateTime test error in HHVM, refs #153, closes #160

## 0.9.8 (2013-12-10)

* Added PSR-4 autoload suppoort
* Moved all classes to lib directory, use PSR-4 to load classes
* Simplified setAutoloadMap method
* Enhances env service loadConfigFile method, allows to pass the file format to load config file
* Added $data parameter for record class save method
* Marks widget functnion as deprecated and will remove in 0.9.9

## 0.9.8-RC1 (2013-12-06)

* Simplified isRecordExists validator logic
* Renamed namespace to `Wei`
* Merged json service into response service
* Renamed record class __get and __set methods to get and set
* Added lock service
* Added counter serviced, with incr, decr, get, set & exists methods
* Added foreach support for record query builder
* Added request and response service as parameters for application action method
* Added desc and asc methods for query builder
* Fixed primary key value error when primary key value is set
* Renamed cache service "keyPrefix" option to "prefix"
* Simplified all cache services, removed all "doXXX" methods
* Added example for logger service to ouput in browser
* Removed %channel% from default log format
* Fixed startWith and endsWith validators test error when "findMe" option is array and contains special regular expression characters
* Added original error message for mkdir error
* Removed arr service
* Added IteratorAggregate support for record class
* Added countable for record class
* Merged query builder, collection class into record class
* Updated record class to returns false when no data found for find method
* Added toArray support for collection record
* Removed getDb method in record class, receive db service on demand
* Added findOne method for record class, when record not found, findOne method throw a 404 exception
* Renamed getLastSql to getLastQuery, be consistent with getQueries
* Speed up findOrCreate method, created only one record object
* Added PHP memory cache for table field names
* Added isPositiveInteger validator
* Added isNaturalNumber validator
* Renamed db service findOrCreate method to findOrInit
* Added setMessage for callback validator
* Fixed arrayCache service remove method always return true issue
* Fixed mongoCache service remove non-exits cache return true issue
* Removed monolog service
* Added isDestroyed method for record class
* Added setBaseUrl & getBaseUrl methods for asset service
* Added isPage for request service
* Added magic set method for service container
* Added getServices method for service container
* Check if record is destroy before save
* Uses "info" level for normal HTTP exceptions
* Make sure app service action method must not starts with "_" and case sensitive
* Make sure app service handleResponse return a response service
* Renamed env service "env" option to "name"
* Renamed env service "configDir" option to "configFile"
* Renamed env service "envMap" option to "ipMap"
* Added exception for cache service when provided a invalid expire time
* Renamed app service namespace option to controllerFormat
* Merged redirect into response service

## 0.9.7 (2013-10-31)

* Added field parameter for record class getModifiedData to receive the old value
* Fixed call and db service "global" option logic error
* Pass weChatApp service $content variable by reference
* Added getResponseHeaders for call service
* Returns array when getResponseHeader's second parameter is false
* Added header option (equals to CURLOPT_HEADER) for call service
* Added json and jsonp method for response

## 0.9.7-RC1 (2013-10-28)

* Merged urlDebugger service into request service, added overwriteMethod and overwriteAjax options for request service
* Added isVerifyToken method for weChatApp service, close #146
* Added beforeSend callback for WeChatApp service
* Added asset service to resolve javascript and css file cache
* Make sure all WeChatApp service text rules is case insensitive
* Renamed WeChatApp service "fallback" method to "defaults"
* Simplified WeChatApp service parse XML logic
* Refactored WeChatApp service to use array instead of SimpleXMLElement to construct response content
* Removed keyword property in WeChatApp service
* Refactored class from Mongo to MongoClient, refs #129
* Refactored url service to generate URL by specified URL and parameters instead of predefined template
* Fixed cache object is null when set new driver for cache service
* Added getFirstMessage for base validator
* Added findOne for db service
* Injects app service into controller object
* Renamed service property from "deps" to "providers"
* Added exception code (1020) for store callback cache error
* Automatic loads record in query builder when called magic get/set or array access
* Added luhn validator, refs #148
* Added getModifiedData method for record class
* Decoupled db and cache service, moved get fields logic to record class
* Added skeleton application demo "new-app"
* Triggers "afterLoad" callback when calls record's reload method
* Added getAttrs method for WeChatApp service

## 0.9.6 (2013-10-09)

* Fixed record status error when record is created from query builder
* Added `getCurlInfo` method for call service
* Close the cURL session on call service destruct
* Added doc for password service and isPassword validator
* Added options parameter for datetime validators
* Renamed isEmpty validator to isPresent

## 0.9.6-RC1 (2013-10-05)

* Added ArrayAccess support for db record class
* Fixed create db record error when key name is class property name
* Added `getIterator` method for session service
* Removed `weChatApp` deprecated FuncFlag
* Added `beforeSend`/`afterSend` callback for response service
* Added `reconnect` method for db service
* Added `insertBatch` method for db service, refs #137
* Added master-salve db support for db service, refs #133
* Updated query exception to `PDOException`, while previous is `RuntimeException` and hard to catch
* Added `toJson` method for record class
* Added callbacks for record class
* Added `reload` method for record class
* Added `beforeValidate` callback for validate service
* Added `removeField` method for validator service
* Added `toArray` method for session service
* Added `exists` method for session service
* Fixed record status error after created
* Renamed all methods from `inXxx` to `isXxx`
* Updated return annotation from class name to $this for better code hint
* Added `is` method for env service to detect custom env name
* Renamed Widget::create to Widget::getContainer, add Widget::setContainer method
* Improve record class `toArray` method
* Added support to generate database table fields
* Renamed inconsistent method from getRules to getFieldRules
* Merged `is` validator into `validate` service
* Removed developer option `disableSslVerification`, use cURL option `CURLOPT_SSL_VERIFYPEER` instead
* Added support to parse response header only when CURLOPT_HEADER is true
* Added `getCurlOption` and `setCurlOption` methods for call service
* Added optional parameter $field for record class `isModified` method
* Added `throwException` option for call service
* Renamed db service globalCallbacks option to global
* Added array parameters support for logging message
* Added support for parsing and return muliti response headers
* Added arrayAccess, count, foreach support for call service
* Added `__toString` method for call service to return the response body
* Added `phone` validator
* Added `password` service to genrate secure password
* Fixed validate flow not break when required rule is invalid
* Added `password` validator

## 0.9.5 (2013-09-08)

* Added `getIterator` method for request and cookie services
* Added `fields` property for record class
* Merged flush, download service into response service, refs #135
* Added headers for redirect service when send by custom view

## 0.9.5-RC1 (2013-09-04)

* Added support for env service to get server IP in CLI mode
* Added key prefix for cache services
* Fixed MySQL cache return false when cache value not changed
* Added new function `wei`, which means 微(micro) in Chinese
* Added `getLastSql`, `getQueries` method for db service
* Added `getUrl`, `getMethod`, `getData`, `getIp` for call service
* Removed request service dependence in most services
* Removed server, post, query, header, twig, smarty, dbal, entityManager, event services
* Added `getConfig` and `setConfig` for service container, removed `config` method
* Added new `config` service, ref #128
* Merged `map` service into `config` service, refs #131
* Renamed `Widget\Stdlib\AbstractCache` to `Widget\BaseCase`
* Removed `Widget\Validator\ValidatorInterface` class
* Added parameters for `db` service `afterQuery` callback
* Fixed `Widget\Db\Record` class save method return false when no field value changed
* Changed cache services' `inc` and `dec` methods to `incr` and `decr`
* Added `isModified` method and `modifiedData` property for `Widget\Db\Record` class
* Added `setAliases`, `setDeps` and `isInstanced` methods for service container
* Added support for empty where condition to `Widget\Db\QueryBuilder` class
* Changed log level priorities to adapted with `Monolog`
* Added shorthand method `service()->redis()` to get original \Redis object
* Rename service container callback options from `construct` & `constructed` to `beforeConstruct` & `afterConstructed`
* Added `setPrimaryKey` & `getPrimaryKey` method for `Widget\Db\Record`
* Renamed base class from `Widget\AbstractWidget` to `Widget\Base`
* Fixed `startsWith` and `endsWith` validator error when `findMe` option is int
* Merged `Widget\Validator\BaseGroupValidator` into `Widget\Validator\SomeOf` validator
* Added name parameter for rule valdiator `getMessages` and `getJoinedMessage` methods
* Added `formatLog` method for `logger` service
* Added `isCharLength` validator
* Added expire time support for `dbCache` service
* Renamed `equals` validator to `equalTo`, refs #134
* Added new validator services: `identicalTo`, `greaterThan` and `lessThan`
* Renamed `max` and `min` validators to `lessThanOrEqual` and `greaterThanOrEqual` validators

## 0.9.4 (2013-08-07)

* Changed cache services' `increment` and `decrement` methods to `inc` and `dec`
* Refactored dbCache, use db service instead of dbal service to execute SQL
* Added isNew method for record class
* Added host, port and more options for db service, removed DSN option

## 0.9.4-RC1 (2013-08-02)

* Merged cache service's `cached` method into `get` method
* Added `indexBy` method for query builder
* Added support that automatically create dependence map when configuration key contains ".", refs 6ca934c7fb79956f804641c3dc127a8789e03961
* Fixed query builder parameter number error when parameter value is 0 or null, refs 719aec608e748bbc36089fe20a08d56bebe54f15
* Fixed test error for PHPUnit < 3.7.0
* Used db service instead of dbal service as recordExists validator db provider
* Added [map](lib/Widget/Map.php) service that handles key-value map data
* Added `jsonObject` dataType for `call` service
* Removed `__invoke` method, `__invoke` method is optional, refs 7c7f13e3702c11bd5f35f4e9dcc74598e6cd72b3
* Added getResponseJson method for `call` service
* Allow `null` value as validate rules and messages, refs 132b13dcda99dbd56d596cc50dff13bba8a48c38
* Added `autoload` paramter for import method
* Removed `WidgetAwareInterface` and `AbstractWidgetAware` class, use `service()` is more convenient
* Changed session namespace default to false
* Added getResponse, getErrorStatus, getErrorException methods, disableSslVerification option for call service, refs #86
* Added global option, connectFails callback, getUser and getPassword methods for db service
* Fixed memcache and memcached option error

## 0.9.3 (2013-07-04)

* Fixed [WeChatApp](lib/Widget/WeChatApp.php) click event letter case typo
* Added introduction, installation, and configuration documentations
* Added new demos : using YAML/JSON as service configuration
* Added driverOptions for db service

## 0.9.3-RC1 (2013-06-26)

* Added `callback` service to handle WeChat(Weixin) callback message
* Added `overwrite` option for [upload](lib/Widget/Upload.php) service
* Added support for upload file without extension
* Added `getMulti` and `setMulti` method for cache services
* Added code completion supports for services, refs [AbstractWidget](lib/Widget/AbstractWidget.php)
* Removed all root namespace in docblock, refs a5db92949346b38adfc8818ba9aa3f70eb7cbdef
* Added new service: [arrayCache](lib/Widget/ArrayCache.php)
* Removed `inMethod` service
* Added new API documentation: http://twinh.github.io/service/
* Added `getJoinedMessage` method for validators, refs #52
* Simplified [cookie](lib/Widget/Cookie.php) service options, refs fa88083a742f7aa7e8d6d1829a34e4ca853fb50a
* Removed `inGet`, `inPost`, `inAjax` service, use [request](lib/Widget/Request.php) service instead
* Merged `sort` and `attr` services in to [arr](lib/Widget/Arr.php) service
* Refactored [dbCache](lib/Widget/DbCache.php) service
* Added [couchbase](lib/Widget/Couchbase.php) service
* Changed license to MIT
* Added [isColor](lib/Widget/Validator/Color.php) validator
* Added [mongoCache](lib/Widget/MongoCache.php) service
* Added [call](lib/Widget/Call.php) service
* Merged validator into [validate](lib/Widget/Validate.php) service
* Added global function `service` to make it easy to receive service manager
* Refactored error service, moved `exception`, `fatal` and `notFound` event to error service
* Removed marker service
* Added `cached` method for cache services
* Renamed eventManager service to [event](lib/Widget/Event.php)
* Renamed isPostCode to [isPostcodeCn](lib/Widget/Validator/PostcodeCn.php)
* Added `Stdlib` namespace, moved base cache, view, service aware class to `Stdlib` namespace
* Renamed `db` service to [dbal](lib/Widget/Dbal.php)
* Added new [db](lib/Widget/Db.php) service with basic CURD, light Active Record and Query Builder support
* Refactored [env](lib/Widget/Env.php) service, use server IP to detect environment name
* Added [gravatar](lib/Widget/Gravatar.php) service
* Added support for Chinese mobile number starts with 14
* Added documentation for [service](docs/zh-CN/service.md) class
* Moved debug configuration to service class
* Added [FieldExists](lib/Widget/Validator/FieldExists.php) validator
* Removed WidgetInterface, ViewInterface, CacheInterface and ArrayWidget class, make it more esay
* Integrated with [Coveralls](https://coveralls.io/?), and [Codeship](https://www.codeship.io/)
* Refactored [cookie](lib/Widget/Cookie.php) and [response](lib/Widget/Response.php) services
* Added more tests and improved more documentation

## 0.9.2-beta (2013-04-14)

* Released first beta version
* Added unit test and fixed lots of error for all services
* Added validation component
* Added first version of API documentation

## 2012-08-30
* Moved to GitHub

## 2010-07-25
* First commit in Google Code

## 2008-07-01
* Project startup
