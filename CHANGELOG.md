## [0.15.9](https://github.com/twinh/wei/compare/v0.15.8...v0.15.9) (2023-06-30)


### Features

* **BaseController, experimental:** 增加 `init` 方法 ([00fc885](https://github.com/twinh/wei/commit/00fc885444bbab8213d9f17badcf0c08d022a628))

## [0.15.8](https://github.com/twinh/wei/compare/v0.15.7...v0.15.8) (2023-05-31)


### Features

* **Migration:** `rollback` 允许只回退指定的一个编号 ([9e9a0b3](https://github.com/twinh/wei/commit/9e9a0b3ff68cb56bd5afa6359adbf1688970e660))
* **Migration:** `target` 允许传入大写开头的类名 ([6088573](https://github.com/twinh/wei/commit/60885737ae4ad62cf8d806fe86fea3635ffe075f))
* **Res:** 增加 `getHeaders` 方法 ([160f161](https://github.com/twinh/wei/commit/160f16100f1453368451bf75dc9d958df44c4b6b))





### Dependencies

* **@miaoxing/dev:** upgrade from `8.2.0` to `8.2.1`

## [0.15.7](https://github.com/twinh/wei/compare/v0.15.6...v0.15.7) (2023-04-30)


### Features

* **Http:** 增加 `requestRet` 方法 ([dd35fab](https://github.com/twinh/wei/commit/dd35fab6f1db8734072a9ce7ac683c84c0eeb8e4))
* **Http:** 增加 `toRet` 方法，用于将请求结果转换为 `Ret` 对象 ([70aa7fa](https://github.com/twinh/wei/commit/70aa7fa7f0ef67c7819239d31cea53e96f61e2af))

## [0.15.6](https://github.com/twinh/wei/compare/v0.15.5...v0.15.6) (2023-04-15)


### Bug Fixes

* **V:** `json` 列允许存入数组，改用 `isJson` 校验 `json` 列 ([432790f](https://github.com/twinh/wei/commit/432790f6c452e21d72a9a53a0eecb42d408137a5))


### Features

* **Http:** 增加 `Http::request` 方法，替代 `wei()->http` ([da33434](https://github.com/twinh/wei/commit/da334346990b2e7ae10b2bafe6c49eb4b919535a))
* **isJson:** 增加 `isJson` 校验器，用于校验数据是否为数据库 JSON 数组或对象 ([3363cd9](https://github.com/twinh/wei/commit/3363cd9a318674db103b82cc9ebe1e4256c1bb67))
* **Model, experimental:** 增加 `syncRelation` 用于保存关联表的数据 ([de7ec87](https://github.com/twinh/wei/commit/de7ec876d1466bb4bfde7b7a10fc55437e6a6ef1))
* **Model, experimental:** 支持自定义关联 ([ba0c988](https://github.com/twinh/wei/commit/ba0c9889126aa5edb7d3ab083d467e47f611b871))
* **Ret:** 调用 `err` 方法时，如果 `code` 是成功代码，则转换为错误代码 ([a926125](https://github.com/twinh/wei/commit/a9261250989232dd6bc03f593586e09400fae027))
* **Ret, experimental:** 增加 `prefix` 方法，用于附加内容到信息前面 ([8c81c3f](https://github.com/twinh/wei/commit/8c81c3f153570f222b6682207b7b45344d41c68c))

## [0.15.5](https://github.com/twinh/wei/compare/v0.15.4...v0.15.5) (2023-03-01)


### Features

* **BaseController:** 增加 `getMiddleware` 和 `removeMiddleware` 方法 ([fa3c0b0](https://github.com/twinh/wei/commit/fa3c0b0402b1618f29ef034907ea0757a4592816))
* **classMap:** 支持多级继承的情况 ([6c152f0](https://github.com/twinh/wei/commit/6c152f0df928eaab4a39f048bc707569d64369c8))
* **isModelExists:** 增加 `isModelExists` 校验器 ([ccc2b8f](https://github.com/twinh/wei/commit/ccc2b8f238444c1b8e1bc56253b8de7a9625acd8))
* **Model:** 支持多个记录获取多对多（如 `$articles->tags`）的情况 ([95368e9](https://github.com/twinh/wei/commit/95368e99d8a6e0ae55cc14c468f918d97d72f14f))
* **V:** 增加 `notModelDup` 方法，简化数据库重复值校验逻辑 ([7de73f5](https://github.com/twinh/wei/commit/7de73f5d268e88e7bf9575947194a5a2feb6c227))
* **V:** 增加 `requiredIfNew` 方法，用于校验模型新对象必须有数据 ([921b187](https://github.com/twinh/wei/commit/921b187ca1e1c80674c081cd28ce0b016faf14b2))

## [0.15.4](https://github.com/twinh/wei/compare/v0.15.3...v0.15.4) (2023-01-31)


### Bug Fixes

* **PhpFileCache:** 屏蔽在慢 io 或请求并发时，缓存过期，文件被删除，会出现 "failed to open stream: No such file or directory" 错误 ([0e57b2d](https://github.com/twinh/wei/commit/0e57b2d20271b51bb7231e39741e0d8b67d062fa))
* **QueryBuilder:** 参数传入 `null` 时生成 SQL 错误 ([c902a97](https://github.com/twinh/wei/commit/c902a9715636696ce4308feb643a796fec8f5d24))
* **V:** `getModel` 返回值可能为 `null` ([69ae892](https://github.com/twinh/wei/commit/69ae892c5e0e97d2a3608b9e6e2eee115469fabe))
* **V:** 测试自身数据时，获取校验通过的数据错误 ([79e344a](https://github.com/twinh/wei/commit/79e344a190117b4cdab2993c06af7fbe7bb2f03a))
* **Validate:** 解决 `Req` 对象，`offsetExists` 会忽略 `null` 值，导致传入 `null` 时，认为 key 不存在 ([4c18953](https://github.com/twinh/wei/commit/4c18953b461dc0a8a96cd2a8f142f6e59efff780))


### Features

* **Model, Cast, experimental:** 增加 `ip` 转换 ([39ab6b6](https://github.com/twinh/wei/commit/39ab6b6bfad15fb6d4649db9ed372160c6639451))
* **Snowflake:** 增加 `Snowflake` 服务，用于生成全局唯一 ID ([4cc7fed](https://github.com/twinh/wei/commit/4cc7fed3af03ca172590a49c87e5292c63cd1214))

## [0.15.3](https://github.com/twinh/wei/compare/v0.15.2...v0.15.3) (2023-01-01)


### Bug Fixes

* **Model:** `bool` 类型存储到数据库前要转换为 `int` ([63398ec](https://github.com/twinh/wei/commit/63398ec8fd310d2cc6032a320d8e144d14caa766))
* **Model:** `ModelTrait` 类缺少引入 ([b25fb3d](https://github.com/twinh/wei/commit/b25fb3d7c78832af3eb785b970d40d2d2c51e188))


### Features

* **isTimestamp:** 增加 `isTimestamp` 校验器，用于校验数据是否符合数据库 `timestamp` 字段 ([28e844c](https://github.com/twinh/wei/commit/28e844c59bc6851e0fe21482f0b93f6a722d11d8))
* **isUnique:** 增加 `isUnique` 校验器，用于检查数组不能包含相同的值 ([b215bd0](https://github.com/twinh/wei/commit/b215bd0369e12e824bd026fbdfb4fbaa0265f980))
* **Model:** `Model::update` 对参数进行转换 ([024403b](https://github.com/twinh/wei/commit/024403ba7cd7042033f99ee393ac949a3a925ef5))
* **QueryBuilder:** 增加 `whereNot` 方法 ([540b629](https://github.com/twinh/wei/commit/540b6295c3c92bba6c1f6af6ad597f0c2771d852))
* **Req:** 增加 `getServerIp` 方法 ([488e8a6](https://github.com/twinh/wei/commit/488e8a6da0fc51b2ae77d31048d3d75af8395403))
* **Req:** 增加 `trustedProxies` 选项，允许控制是否信任代理 IP ([cd575b3](https://github.com/twinh/wei/commit/cd575b35b64d18dc577c88b383a8594904ab2f8d))
* **Schema:** 增加 `binary` 和 `varBinary` 方法 ([a3ded88](https://github.com/twinh/wei/commit/a3ded883bd623d8cd3265670769224ef4e112ac4))





### Dependencies

* **@miaoxing/dev:** upgrade from `8.1.3` to `8.2.0`

## [0.15.2](https://github.com/twinh/wei/compare/v0.15.1...v0.15.2) (2022-12-01)


### Bug Fixes

* **isLength:** 解决 `null` 值提示长度未识别 ([3ead9b6](https://github.com/twinh/wei/commit/3ead9b6a6021359c7fa5cb12c58c793bc7dc8550))
* **Model:** `fromArray` 包含虚拟字段触发 `Invalid virtual column: ` 错误 ([f38dc31](https://github.com/twinh/wei/commit/f38dc31160a31fc2ae4db60835d68823a9a8fb10))


### Features

* **Model:** `getColumns` 方法增加返回 `title` ([00dab8d](https://github.com/twinh/wei/commit/00dab8d2dabf367ffb41a5b657daf4572e3b076d))
* **Model:** `getColumns` 方法增加返回 `type`，`unsigned`，`length` 和 `scale` ([53a5992](https://github.com/twinh/wei/commit/53a5992631aebf8488b10f27a3210fed6100c2bd))
* **Model, Cast:** `decimal` 字段，如果传入空值，自动转换为 0 ([0e29613](https://github.com/twinh/wei/commit/0e296138ff60788cc00692cd3db994d835d7332a))
* **Schema:** 增加 `dropSoftDeletable` 方法 ([d8e9b94](https://github.com/twinh/wei/commit/d8e9b94e79b28d49c3120ce7650dca6fc636c19f))
* **Str:** 增加 `truncate` 方法 ([2492499](https://github.com/twinh/wei/commit/249249965972b6518711dfb511bb3d6013753be4))
* **V:** `modelColumn` 名称留空时，从数据库表读取名称 ([97243d6](https://github.com/twinh/wei/commit/97243d6bdc9d2e3e7f043668ac6f562feca7f834))
* **V:** 支持指定数据表字段生成校验规则 ([77f74eb](https://github.com/twinh/wei/commit/77f74eb66e1ab642f480f033e421ff47c4b21cdc))

## [0.15.1](https://github.com/twinh/wei/compare/v0.15.0...v0.15.1) (2022-11-01)


### Bug Fixes

* **V:** `key` 为 `0` 时被识别为校验所有数据 ([66cd782](https://github.com/twinh/wei/commit/66cd7829fc0d2d5b9112668b8e3f548852dd830e))


### Features

* **isImageUrl:** 增加错误信息的中文翻译 ([a67ca6d](https://github.com/twinh/wei/commit/a67ca6dcc1d658b4b367d2193c631b04d20e1f59))
* **isObject, experimental:** 增加长度校验 ([e21c008](https://github.com/twinh/wei/commit/e21c008aa5b1a9d3c975a51cabaa828ee5f81108))
* **isTrue:** 增加 `isTrue` 校验器，用于校验值是否为真，如选中单选框，同意协议 ([1f4ae10](https://github.com/twinh/wei/commit/1f4ae10c29a60ed29bcb941ac94b47839655a4c7))

# [0.15.0](https://github.com/twinh/wei/compare/v0.14.0...v0.15.0) (2022-09-30)


### Bug Fixes

* **idCard:** 简化身份证校验错误的信息 ([824c9b7](https://github.com/twinh/wei/commit/824c9b71d0effd3cfdc24abf1b963f383af69cdc))
* **idCardCn:** 不再支持已经停用的 15 位身份证号 ([f2a8373](https://github.com/twinh/wei/commit/f2a8373d2ab0596fb94869060b5fdc10d848a7ab))


### Code Refactoring

* **env:** 移除通过 `ifconfig` 获取本机 ip 的功能 ([a05d019](https://github.com/twinh/wei/commit/a05d01903574b1f95caff4a326cb3f63d6264972))


### Features

* **Db:** 支持跨数据库操作 ([f0e7cd4](https://github.com/twinh/wei/commit/f0e7cd40903bddf0bcd5ce306566d56c86068a78))
* **isAllow:** 增加 `isAllow` 规则，用于遇到指定值则跳过剩下的规则 ([f2da440](https://github.com/twinh/wei/commit/f2da440ec75af4b26c8aa54b49542b5e0d6a1b70))
* **isAllowEmpty, experimental:** 通过校验器类型自动识别允许的空值 ([2b8f9e3](https://github.com/twinh/wei/commit/2b8f9e32248b40a57a3a50bcb80fac1c4702e21c))
* **isCallback:** 回调允许返回字符串表示错误信息 ([59dabd7](https://github.com/twinh/wei/commit/59dabd73a8fdbdfac742c896e00325a674d7567c))
* **isEmpty, experimental:** 通过校验器类型自动识别允许的空值 ([dd99ebf](https://github.com/twinh/wei/commit/dd99ebf702da29922c283c0061bb4dfaa10e7071))
* **isIdCardCn:** 增加长度错误提示 ([7c778cd](https://github.com/twinh/wei/commit/7c778cd9438594d24243787054633769ba4b9ea2))
* **isImageUrl:** 增加 `isImageUrl` 校验器，用于检查字符串（如用户上传的文件）是否为图片地址 ([9082fe7](https://github.com/twinh/wei/commit/9082fe78b219ed405910dcd5eb69265898cc2030))
* **isInConst:** 增加 `isInConst` 校验器，用于校验值在指定类的常量中 ([3fcddcc](https://github.com/twinh/wei/commit/3fcddcc6137d986e23a6120451b5db3b84b60284))
* **Model:** 支持模型表包含数据库 ([db57ed4](https://github.com/twinh/wei/commit/db57ed40abc924a173c2c9a6420244f6c6b236f3))
* **V:** 如果设置了 `notEmpty` 规则，优先校验 ([5864e45](https://github.com/twinh/wei/commit/5864e458d06580c1735c5f4ad3fbdd18a56add61))
* **V, experimental:** 增加 `defaultNotEmpty` 功能 ([d7a4953](https://github.com/twinh/wei/commit/d7a495301cd91a7dc3950cad1d73c1ddde172912))


### BREAKING CHANGES

* **isEmpty, experimental:** `false` 和空数组 `[]` 默认不再认为是空值
* **env:** 移除通过 `ifconfig` 获取本机 ip 的功能
* **idCard:** 移除旧的身份证校验错误的翻译信息，增加新的翻译信息
* **idCardCn:** 不再支持已经停用的 15 位身份证号

# [0.14.0](https://github.com/twinh/wei/compare/v0.13.0...v0.14.0) (2022-09-03)


### Bug Fixes

* **DbCache:** 指定表前缀时出错 ([e0a294f](https://github.com/twinh/wei/commit/e0a294f9662ed3a01745ab1be6609883e52b5185))


### Code Refactoring

* **apc:** 移除失效的 `apc` 缓存 ([e178b00](https://github.com/twinh/wei/commit/e178b0068dde43161cb75171a6422b3c992af651))
* **bicache:** 主缓存默认缓存由 `apc` 改为 `apcu` ([2e74df0](https://github.com/twinh/wei/commit/2e74df0de7811d1731d0d77fc897f5c287ad5b67))
* **cache:** 默认缓存由 `apc` 改为 `phpFileCache` ([fbd12fc](https://github.com/twinh/wei/commit/fbd12fcd8bb418b9fd4fd3c8f1911ad4201cef5f))


### Features

* **apcu:** 增加 `apcu` 缓存服务 ([6ccf765](https://github.com/twinh/wei/commit/6ccf765abfacf52ef08fc74e7d59f842f4ae560e))
* **db:** 增加 `setTablePrefix` 方法 ([04f50eb](https://github.com/twinh/wei/commit/04f50ebb738dd9c16d0100802621ba0a28301a8a))
* **QueryBuilder:** 增加 `orderByRaw` 方法 ([f6a4c54](https://github.com/twinh/wei/commit/f6a4c5499343619b00792b3c84a6fb516b4dcb40))


### BREAKING CHANGES

* **bicache:** `bicache` 主缓存默认缓存由 `apc` 改为 `apcu`
* **cache:** 默认缓存由 `apc` 改为 `phpFileCache`
* **apc:** 移除失效的 `apc` 缓存
* **QueryBuilder:** `QueryBuilder::orderBy` 第一个参数移除类型，允许传入 `Raw` 对象

# [0.13.0](https://github.com/twinh/wei/compare/v0.12.6...v0.13.0) (2022-08-02)


### Bug Fixes

* **IsEach:** `each` 方法传入 `V` 对象报错 ([0b8eb58](https://github.com/twinh/wei/commit/0b8eb58dc29a38a248209ad82f68684807dfcf84))


### Code Refactoring

* **Cls:** 移动 `Str` 到 `wei` 中 ([fedea6a](https://github.com/twinh/wei/commit/fedea6a9890c744594e88924f1945b61e2ebe718))


### Features

* **Cls:** 增加 `cls` 服务，用于处理类相关功能 ([5c8389e](https://github.com/twinh/wei/commit/5c8389e40dc7588f50ca548d13f279b5846242e9))
* **Model:** 移动模型基类到 `wei` 中 ([5c532ca](https://github.com/twinh/wei/commit/5c532ca1ce0d38e52b50a31d35b2112fe37d4202))
* **QueryBuilder:** 移动 `QueryBuilder` 到 `wei` 中 ([d7a3826](https://github.com/twinh/wei/commit/d7a3826b790aaf6d0f985d6c1eae548b3cc1d4c1))
* **Ret, experimental:** 增加 `RetException` ([52b4de3](https://github.com/twinh/wei/commit/52b4de392639b7c62b5cabe4056258f15b613ec5))
* **Str:** 移动 `Str` 到 `wei` 中 ([6897b4d](https://github.com/twinh/wei/commit/6897b4d4076e885dbb4a81ac551e7fce8a809dff))
* **V:** 增加 `assert` 方法，校验成功返回校验过的数据，失败抛出异常 ([1037684](https://github.com/twinh/wei/commit/103768417a711f9bebe75251a83db8e8fcb20d95))
* **V:** 支持通过任意校验器初始化键名校验，重写 `V` 服务 ([b6e2236](https://github.com/twinh/wei/commit/b6e2236a332c4ff5ae02f339fad7bd67bf12c55a))
* **V, experimental:** 增加 `self` 方法，用于校验整个数据，而非数据中的某一项 ([1bed1f4](https://github.com/twinh/wei/commit/1bed1f417204095a02356e7ab90fe5bd7f4672ea))
* **Validator, experimental:** 移除 `BASIC_TYPE` 常量 ([c015f0a](https://github.com/twinh/wei/commit/c015f0ab4dfbbd19cd31b880efe6365023ef3b27))


### BREAKING CHANGES

* **Cls:** 移动 `Str` 到 `wei` 中
* **V:** `V` 服务不再支持串联校验，以便支持通过任意校验器初始化键名校验





### Dependencies

* **@miaoxing/dev:** upgrade from `8.1.2` to `8.1.3`

## [0.12.6](https://github.com/twinh/wei/compare/v0.12.5...v0.12.6) (2022-07-02)


### Bug Fixes

* 解决 composer 2.2+ 默认不启用插件导致安装路径错误 ([](https://github.com/twinh/wei/commit/))

## [0.12.5](https://github.com/twinh/wei/compare/v0.12.4...v0.12.5) (2022-07-01)


### Features

* **NearCache:** 增加 `getFront` 和 `getBack` 方法 ([07d73fe](https://github.com/twinh/wei/commit/07d73fea1d3e2b9cd49ae98cd3f55dcfe9e56f45))





### Dependencies

* **@miaoxing/dev:** upgrade from `8.1.1` to `8.1.2`

## [0.12.4](https://github.com/twinh/wei/compare/v0.12.3...v0.12.4) (2022-06-01)


### Bug Fixes

* **Req:** 请求方式为 `POST` 才可以通过 `_method` 参数来更改请求方式 ([b5d0e91](https://github.com/twinh/wei/commit/b5d0e91f5b708d300a7373621a0f6cbce8f3809f))


### Performance Improvements

* **Req:** 优化判断是否为 ajax 的逻辑 ([948d4da](https://github.com/twinh/wei/commit/948d4daaa8efdb7125405ad8aa154cfcdbb1f179))
* **Req:** 优化获取请求方式的逻辑 ([3162ae7](https://github.com/twinh/wei/commit/3162ae7378ad10317ec862dbd7c022e85fcc7093))





### Dependencies

* **@miaoxing/dev:** upgrade from `8.1.0` to `8.1.1`

## [0.12.3](https://github.com/twinh/wei/compare/v0.12.2...v0.12.3) (2022-04-30)


### Bug Fixes

* **Schema:** 解决 `rename` 表名缺少前缀 ([edfa09d](https://github.com/twinh/wei/commit/edfa09daeecc86d7fc799088947c7aececd4ad2e))


### Features

* **Ret:** 允许 `Ret` 对象转换为字符串 ([2e67678](https://github.com/twinh/wei/commit/2e676789854d6b0af4e360248c1c4584e758d66c))

## [0.12.2](https://github.com/twinh/wei/compare/v0.12.1...v0.12.2) (2022-03-31)


### Features

* **Schema:** `drop`, `dropIfExists` 支持支持一次删除多个表名 ([5429a99](https://github.com/twinh/wei/commit/5429a9965e631f77013c25364d9643009b7b93ac))

## [0.12.1](https://github.com/twinh/wei/compare/v0.12.0...v0.12.1) (2022-02-28)


### Bug Fixes

* **Upload:** 创建目录使用默认的 `0755` 权限，解决 PHP 写入文件 nginx 不可读的问题 ([6f86418](https://github.com/twinh/wei/commit/6f8641887f3d09b3c95c72cd040b936f4e774fdf))


### Features

* **service, experimental:** 更改 `instance` 为静态方法 ([127b91e](https://github.com/twinh/wei/commit/127b91e70d8dd5718a89cf6203e40a75322e2e4b))
* **Upload:** `save` 方法返回值增加 `name`，`type` 和 `size` ([b21604a](https://github.com/twinh/wei/commit/b21604a66799e502bc26b664c0fb684a1340b1a4))
* **Upload:** 增加 `path` 属性，用于指定上传文件的完整路径 ([6842a8b](https://github.com/twinh/wei/commit/6842a8bea23a86d3d44922bfeb4ea802c05acfff))
* **Upload:** 增加 `save` 方法 ([7221d33](https://github.com/twinh/wei/commit/7221d3327f7b413416637e9688798121c49c96f4))
* **Upload:** 增加默认名称 `file` 的中文翻译 ([63703ca](https://github.com/twinh/wei/commit/63703ca952ebbd4a718d8dceaffc238010fa8714))

# [0.12.0](https://github.com/twinh/wei/compare/v0.11.1...v0.12.0) (2022-02-05)


### Bug Fixes

* **Cache:** 缓存无数据返回值由 `false` 改为 `null` ([b46ad12](https://github.com/twinh/wei/commit/b46ad12ed07ebb0a382c612a665cd3ec812a6b4c))
* **Redis:** 解决存储整数读取出来为字符串 ([00ddc90](https://github.com/twinh/wei/commit/00ddc90e905b53578b0f6b7a32c0b11fc1091ef5))


### Code Refactoring

* **Cache:** 移除 `getResultCode` 方法，该方法是 `memcached` 特有的 ([5a6da73](https://github.com/twinh/wei/commit/5a6da7320d231469b4ed8c05f53c280394ff63c7))
* **Cache:** 移除过时的 `Couchbase` 服务 ([2eaa354](https://github.com/twinh/wei/commit/2eaa3546a2d9553f08d69fb42e5cc655ba7cc557))
* **Cache:** 移除通过 `get` 设置缓存的功能，改用 `remember` ([37fd419](https://github.com/twinh/wei/commit/37fd41917bfbe47e042b3465f42f66dbf375390e))
* **Memcached:** `getMulti` 改为直接调用 `getMultiple` ([9039f6b](https://github.com/twinh/wei/commit/9039f6b7cda18ad1b08ab4e67bb00e0e7ff1bf9f))


### Features

* **Cache:** 允许静态调用缓存方法 ([d5eb300](https://github.com/twinh/wei/commit/d5eb3005e2754a05a35425c4498214b211180909))
* **Cache:** 增加 `has`，`delete`，`getMultiple` 和 `setMultiple` 方法，废弃 `exists`，`remove`，`getMultiple` 和 `setMultiple` 方法 ([3bf9df0](https://github.com/twinh/wei/commit/3bf9df0ad708784531bb250ff53793c616b140cb))
* **Cache:** 增加 `isHit` 方法用于判断 `get` 或 `getMultiple` 获取的缓存是否存在 ([25ddb69](https://github.com/twinh/wei/commit/25ddb6916881af288a36088bfb61884137bade88))
* **Cache:** 增加 `remember` 方法，用于缓存回调生成的数据 ([8493b4f](https://github.com/twinh/wei/commit/8493b4f55ae5b7db8fe297fb0285e1f5fdda58f4))
* **Cache:** 缓存驱动增加 `isHit` 支持 ([8c05617](https://github.com/twinh/wei/commit/8c05617348ab0a81cc73c35ff99aa6d619d1a4e9))
* **Cache:** 默认值支持通过闭包生成 ([e285407](https://github.com/twinh/wei/commit/e2854075239c757ef11d4bc44b75f83d0b4e234a))
* **Db:** 允许静态调用 `Db::transactional` 方法 ([15e90f2](https://github.com/twinh/wei/commit/15e90f2fd78b9bf575d4063aa8b8eb5dcc566999))
* **Env:** 允许静态调用 `Env::is*` 方法 ([2897cd1](https://github.com/twinh/wei/commit/2897cd1c0df23f3cb27f061c4eb84cd62440a915))
* **Memcached:** `getMultiple` 支持 `isHit` 判断缓存是否存在 ([fe90ee6](https://github.com/twinh/wei/commit/fe90ee6e8da46aef9875cbee8c95c762d05b0a0a))
* **NullCache:** 增加 `NullCache` 服务 ([b0ddc66](https://github.com/twinh/wei/commit/b0ddc6607192ef832b6399f4ac5ee8494a7982a9))
* **Redis:** `getMultiple` 支持 `isHit` 判断缓存是否存在 ([f4f7f75](https://github.com/twinh/wei/commit/f4f7f75b4ad9e12eb4d6deb0514fd4f4d09958ee))
* **Redis:** `setMultiple` 方法改为调用 `mset`，支持一次性设置 ([eb8ab65](https://github.com/twinh/wei/commit/eb8ab657cc633a21497c9dbad6b6f1846c1f6711))
* **service:** 增加 `instance` 方法来获取当前类对应的服务对象 ([debc3f3](https://github.com/twinh/wei/commit/debc3f3cc953fca53f0cef1f0a5384c8d313ae39))
* **TagCache:** 支持 `getMultiple`，`setMultiple` 和 `isHit` 方法 ([6a007c9](https://github.com/twinh/wei/commit/6a007c94e01953ad4ebff49718135935c52977ad))
* **wei:** 增加 `getBy` 方法，用于传入类名，获取对应的服务 ([26b4632](https://github.com/twinh/wei/commit/26b4632dbbfc84849b22c149091267640ab62ad2))
* **Wei:** 增加 `removeConfig` 方法 ([84137c7](https://github.com/twinh/wei/commit/84137c740acab0aae538ca3640ccddc3d3b75b8a))


### BREAKING CHANGES

* **Memcached:** `Memcached` 服务移除 `isMemcached3` 属性
* **Cache:** 移除 `getResultCode` 方法，该方法是 `memcached` 特有的
* **Cache:** 移除通过 `get` 设置缓存的功能，改用 `remember`
* **Cache:** 移除过时的 `Couchbase` 服务
* **Cache:** 缓存无数据返回值由 `false` 改为 `null`
* **Redis:** 解决存储整数读取出来为字符串
  * 原来存入的字符串数字会被读取为整形或浮点数
  * 新存入的数据，如果需要调用 `in/decrement`，需使用整形，不可使用字符串




### Dependencies

* **@miaoxing/dev:** upgrade from `8.0.1` to `8.1.0`

## [0.11.1](https://github.com/twinh/wei/compare/v0.11.0...v0.11.1) (2022-01-12)


### Bug Fixes

* **wei, Error:** 解决错误时，使用 `response` 对象发送响应缺少 header 的问题 ([d4fe44d](https://github.com/twinh/wei/commit/d4fe44d8ce7dba3549579d206d7e4f1bf5743c05))





### Dependencies

* **@miaoxing/dev:** upgrade from `8.0.0` to `8.0.1`

# [0.11.0](https://github.com/twinh/wei/compare/v0.10.11...v0.11.0) (2021-10-28)


### Bug Fixes

* **Db:** 不缓存 dsn，每次应实时生成 ([33a525a](https://github.com/twinh/wei/commit/33a525acd6cbb6fa1c5e02f97d02513bda02070c))
* **Migration:** migrations 为空时处理错误 ([b1bef9a](https://github.com/twinh/wei/commit/b1bef9a9355246163e7bacf2a871f9641fd42c8b))
* **Migration:** 构造函数的参数允许为空数组 ([b2d6239](https://github.com/twinh/wei/commit/b2d62398e73b839177c71b248c8e78cf6e025d73))
* **wei:** 子类通过 `static::` 调用父类私有常量会出错 ([19ec9f2](https://github.com/twinh/wei/commit/19ec9f22b248b094356e4b043cd11490ea648533))


### Code Refactoring

* **Redis:** 去除内置的`SERIALIZER_PHP`，改用 Laravel 的 `serialize` 逻辑 ([1b296fa](https://github.com/twinh/wei/commit/1b296fa724346ff5b47542e8b6297301cac4c0b2))
* **Wei:** 配置分隔符改用 `.`，服务名称分隔符改用 `:` ([f0c921b](https://github.com/twinh/wei/commit/f0c921b22c97d0bed07a18c6e67667e5504f109c))


### Features

* **db:** 增加 `raw`，`getRawValue` 和 `isRaw` 方法来生成和处理原生 SQL 值 ([899e8f9](https://github.com/twinh/wei/commit/899e8f9d635d7d4fa9ac19aab972592be81a9c1b))
* **Migration:** 增加 `reset` 方法，用于回滚所有的迁移脚本 ([b292617](https://github.com/twinh/wei/commit/b292617209d4a0677ebe8d38af7de70f3e3cc91c))
* **Req:** 允许通过 `HTTP_X_HTTP_METHOD_OVERRIDE` 标头来覆盖请求方法 ([320f03d](https://github.com/twinh/wei/commit/320f03de311afd61dcab5f491e1154784352df6b))
* **Req:** 增加 `getHeader` 和 `hasHeader` 方法 ([aa4dce0](https://github.com/twinh/wei/commit/aa4dce0b301aa6e48fe5d966394347a23d1b99ef))
* **Req:** 增加 `isPreflight` 方法 ([ae4a261](https://github.com/twinh/wei/commit/ae4a2612e4ab140b764c73be967e97c4ead57bfd))
* **Ret:** 增加 `with` 和 `data` 方法用于附加数据 ([6160708](https://github.com/twinh/wei/commit/61607081669ade6109112e9878c15c3c871db091))
* **Ret:** 增加 getter 和 setter，用于获取 `code`，`message`，`data` 和自定义数据 ([e93ddbe](https://github.com/twinh/wei/commit/e93ddbec3c013765b3980fde015fe5aa67cfa91b))
* **Ret, experimental:** 增加 `include` 和 `includeWith` 方法，用于通过 URL 指定参数来附加数据 ([a349eff](https://github.com/twinh/wei/commit/a349eff416610d3f54d986d212471c456abefd0c))
* **Ret, experimental:** 增加 `transform` 方法，用于转换 `data` ([d225bb2](https://github.com/twinh/wei/commit/d225bb2cc4134f7c5b20de54daf24c61d50bcb92))
* **Schema:** 增加 `hasDatabase`，`createDatabase` 和 `dropDatabase` 方法 ([686e5fc](https://github.com/twinh/wei/commit/686e5fcf7da485d19e4eecc7fc9989848a7f415d))
* **Schema:** 增加 `userIdType` 属性和方法，用于自定义用户编号字段的类型 ([0614a46](https://github.com/twinh/wei/commit/0614a4637271a355a0879d64fe24f35f2323b09f))
* **Validator:** 增加 `check` 方法，用于检查后返回一个 `Ret` 对象 ([5aa45d2](https://github.com/twinh/wei/commit/5aa45d2dc5519164b67006791806a6a3094a55fd))


### BREAKING CHANGES

* **Redis:** Redis 服务去除内置的`SERIALIZER_PHP`，改用 Laravel 的 `serialize` 逻辑
* **Wei:** 配置分隔符改用 `.`，服务名称分隔符改用 `:`
* **db:** 移除通过 `(objet)` 生成原生 SQL 值，改为通过 `raw` 方法生成





### Dependencies

* **@miaoxing/dev:** upgrade from `7.0.1` to `8.0.0`

## [0.10.11](https://github.com/twinh/wei/compare/v0.10.10...v0.10.11) (2021-05-21)


### Bug Fixes

* **Validator:** 幂计算符号错误，整数溢出变浮点数 ([dcb6980](https://github.com/twinh/wei/commit/dcb6980a75f714c2a94d2216fe01a685274d94fd))

## [0.10.10](https://github.com/twinh/wei/compare/v0.10.9...v0.10.10) (2021-05-12)





### Dependencies

* **@miaoxing/dev:** upgrade from `7.0.0` to `7.0.1`

## [0.10.9](https://github.com/twinh/wei/compare/v0.10.8...v0.10.9) (2021-05-11)


### Features

* **wei:** 增加 psr-4 自动加载 ([cb11217](https://github.com/twinh/wei/commit/cb11217bd9af7f2f64856adade8a71742dfb7275))





### Dependencies

* **@miaoxing/dev:** upgrade from `6.4.0` to `7.0.0`

## [0.10.8](https://github.com/twinh/wei/compare/v0.10.7...v0.10.8) (2021-04-27)


### Features

* **IsObject:** 增加 `IsObject` 校验规则 ([08c36e8](https://github.com/twinh/wei/commit/08c36e8494fa393bc1fdbd71423ff6ee7b92191d))





### Dependencies

* **@miaoxing/dev:** upgrade from `6.3.4` to `6.4.0`

## [0.10.7](https://github.com/twinh/wei/compare/v0.10.6...v0.10.7) (2021-03-22)





### Dependencies

* **@miaoxing/dev:** upgrade from `6.3.3` to `6.3.4`

## [0.10.6](https://github.com/twinh/wei/compare/v0.10.5...v0.10.6) (2021-03-12)





### Dependencies

* **@miaoxing/dev:** upgrade from `6.3.2` to `6.3.3`

## [0.10.5](https://github.com/twinh/wei/compare/v0.10.4...v0.10.5) (2021-03-10)





### Dependencies

* **@miaoxing/dev:** upgrade from 6.3.1 to 6.3.2

## [0.10.4](https://github.com/twinh/wei/compare/v0.10.3...v0.10.4) (2021-03-09)





### Dependencies

* **@miaoxing/dev:** upgrade from 6.3.0 to 6.3.1

## [0.10.3](https://github.com/twinh/wei/compare/v0.10.2...v0.10.3) (2021-03-09)





### Dependencies

* **@miaoxing/dev:** upgrade from 6.2.0 to 6.3.0

## [0.10.2](https://github.com/twinh/wei/compare/v0.10.1...v0.10.2) (2021-03-05)


### Bug Fixes

* PHP 7.4 Trying to access array offset on value of type float|null|bool 错误 ([3301cbe](https://github.com/twinh/wei/commit/3301cbe7e9ee15d553e10e644b00a59341ccd2aa))

## [0.10.1](https://github.com/twinh/wei/compare/v0.10.0...v0.10.1) (2021-03-05)





### Dependencies

* **@miaoxing/dev:** upgrade from 6.1.2 to 6.2.0

# [0.10.0](https://github.com/twinh/wei/compare/v0.9.31...v0.10.0) (2021-03-05)


### Bug Fixes

* **Between:** 解决参数传入 0 时校验错误 ([2844408](https://github.com/twinh/wei/commit/2844408a988c56cc6cd61d93c9986d084a519046))
* **FileCache:** 忽略慢 io（docker）和请求并发导致同时调用删除文件时错误 ([25ab8b4](https://github.com/twinh/wei/commit/25ab8b41dd4a69a618b8ff9564f3a90d2a7a6089))
* **IsEach:** 每次校验时都要创建一个新的 `V` 对象，以便对每个数组项目自定义规则 ([6a47768](https://github.com/twinh/wei/commit/6a477681dcfe451d772175be5c7b5a4dae880e68))
* **IsTinyChar:** 增加 `IsTinyChar` 校验规则 ([4178fc9](https://github.com/twinh/wei/commit/4178fc95bad184ed6b6845a05f9a3a99ddef4dda))
* **Logger:** 输出日志内容由 `print_r` 改为 `json_encode`, 解决复杂变量可能导致内存溢出的问题 ([efded05](https://github.com/twinh/wei/commit/efded05fc4cd6964ef5e6dc168c8b6f2ee4f9ef9))
* **Req:** 调用 `clear` 后再调用 `getData` 可能出现 `Undefined array key "xxx"` 的错误 ([d50987e](https://github.com/twinh/wei/commit/d50987e1f09ab430736615127fd39cf3b87cddbf))
* **V:** 校验规则传入关联数组作为选项无效 ([b62cd31](https://github.com/twinh/wei/commit/b62cd3156984f1def094bb5f7626899c39d46e2d))
* **Validate:** `hasField` 判断数据错误 ([298a614](https://github.com/twinh/wei/commit/298a614ada7fef6123a7ba97d0d55a8bd25597f8))
* **Validate:** data 传入闭包抛出 Error : Closure object cannot have properties ([d6d6989](https://github.com/twinh/wei/commit/d6d698974ba249b7337f8bb69d2289ea9cf410f6))
* **Validate:** PHP8 method_exists 需传入对象或类名字符串 ([174ca14](https://github.com/twinh/wei/commit/174ca140be91293855ce1b3c00c87391d19d0e75)), closes [#1](https://github.com/miaoxing/miaoxing/issues/1)
* **Validator:** 指定 subValidators 的默认值为数组 ([1094631](https://github.com/twinh/wei/commit/109463144f9e47b8651adc9993dfed6e22602a69))
* **Validator:** 指定了 `countByChars` 选项后，如果校验的值不是字符串值，则返回失败 ([31980b3](https://github.com/twinh/wei/commit/31980b36e241ef99415b08c2f92428f73e91b62b))
* 更正生成 Migration 的基类的命名空间 ([b124bfa](https://github.com/twinh/wei/commit/b124bfa008ce6b11234e44d82b8517a1785b06b0))


### Features

* **IsArray:** 增加 `minLength`, `maxLength` 选项 ([c6dc44c](https://github.com/twinh/wei/commit/c6dc44cbb93f78e06a1450bb457dfe25a8c04789))
* **IsCallback:** 增加 `getValidator` 方法，可用于回调中获取当前校验器的信息 ([c3718cb](https://github.com/twinh/wei/commit/c3718cb2bae5adc5f94b0c246990128c33b2564f))
* **IsEach:** `each` 校验器支持获取当前校验数据和键名 ([182852e](https://github.com/twinh/wei/commit/182852eb26a382210915e8a6a151aef637f3b686))
* **IsEach:** 增加 IsEach 校验规则，用于校验二维数组 ([8dc17e7](https://github.com/twinh/wei/commit/8dc17e7e1d7c4d5abeef6e297d2c70b07856a6b1))
* **IsEmpty:** 增加 `IsEmpty` 校验规则 ([88dd648](https://github.com/twinh/wei/commit/88dd6480bab8501b98e05efc9d0d7526506e637a))
* **IsInt:** 增加 `min`, `max` 选项 ([04ab90f](https://github.com/twinh/wei/commit/04ab90f8a43044da6b67fab3cdf7494477712edc))
* **IsString:** 增加 `minLength` 和 `maxLength` 选项 ([962ace0](https://github.com/twinh/wei/commit/962ace0f47e57344a9c5829302eb07d28ca21d78))
* **IsText, IsMediumText:** 增加 `IsText` 和 `IsMediumText` 校验规则 ([6c6e1da](https://github.com/twinh/wei/commit/6c6e1da892b22bd20c47708734f811f19407845c))
* **Schema:** 增加 `bigId()` 方法，用于生成 bigint 类型的自增 id ([0036247](https://github.com/twinh/wei/commit/0036247f69040b16c8311d0c703c20e87a45e156))
* **Schema:** 增加 json 类型 ([2ec9326](https://github.com/twinh/wei/commit/2ec9326340ca4d84014a3e40aac062d518c45c00))
* **V:** 允许使用基础类型（如 `string`）来创建一行新的数据校验 ([b46b327](https://github.com/twinh/wei/commit/b46b327d08143fee82e5b256ac210b9992b24663))
* **V:** 增加 `allowEmpty` 方法，允许字段为空值时不校验 ([245c048](https://github.com/twinh/wei/commit/245c0483e4fa7b3bbda8e584c1dbb87554178aa7))
* **V:** 增加 `new` 方法，用于返回新的校验器实例 ([f813b71](https://github.com/twinh/wei/commit/f813b71f5ab7c6d957508223a093c9471e329785))
* **V:** 增加 defaultRequired 选项，用于控制所有数据是默认必填还是选填 ([de4aed7](https://github.com/twinh/wei/commit/de4aed74c7fbf0c795d9f0300dfab30dc6ff967f))
* **V:** 增加 getOptions 方法，返回用于 Validate 服务的配置 ([f3e33d3](https://github.com/twinh/wei/commit/f3e33d3fd784ff48a733b12e69293db17e803f04))
* **Validate:** 增加 `getCurrentRule` 方法 ([6406806](https://github.com/twinh/wei/commit/64068066e3da532d5afe7cd9da513084b549e0b0))
* **Validate:** 增加 getFlatMessages 方法，用于获取一维数组的错误信息 ([023b60b](https://github.com/twinh/wei/commit/023b60b1a23df89371dec3fdb61ef24005107be0))
* **Validator:** Number 增加总长度和小数长度选项 ([22e32eb](https://github.com/twinh/wei/commit/22e32ebe24cb7272910958f43bfa697a8bbc1b39))
* **Validator:** 增加 `IsTiny`, `IsSmallInt`, `IsMediumInt`, `IsDefaultInt`, `IsBigInt`,`IsUTiny`, `IsUSmallInt`, `IsUMediumInt`, `IsUDefaultInt` 和 `IsUBigInt` ([3562913](https://github.com/twinh/wei/commit/35629137e4e873b011e2b7708ae289e4a0acfb65))
* **Validator:** 增加 ArrayVal 规则，用于校验数据是否为数组 ([981069a](https://github.com/twinh/wei/commit/981069a367622907b0910ca2abfabe578ea8b2bf))
* **Validator:** 增加 BoolVal 和 Boolable 规则，用于校验数据是否为布尔值 ([23ba996](https://github.com/twinh/wei/commit/23ba996d1d39f932da42b5e38e8517cb9f5fd323))
* **Validator:** 增加 Children 规则，用于校验多级数组 ([9c39028](https://github.com/twinh/wei/commit/9c3902816b32a3bbe12b9fce43d6bda6d1ff7466))
* **Validator:** 增加 FloatVal 规则，用于校验数据是否为浮点数值 ([1f735e7](https://github.com/twinh/wei/commit/1f735e7f8ccc92edb063f3161ff24399f9eb4981))
* **Validator:** 增加 IntVal 规则，用于校验数据是否为整数值 ([31ab9ce](https://github.com/twinh/wei/commit/31ab9ce1ceb7618c8bbb102278776dd1d007f7c3))
* **Validator:** 增加 MaxAccuracy 规则，用于校验数字的最大小数位数 ([526f67f](https://github.com/twinh/wei/commit/526f67fcd3707e944919bdd3995a3282d2666ec9))
* **Validator:** 增加 MinCharLength 和 MaxCharLength 规则 ([438d97d](https://github.com/twinh/wei/commit/438d97d91d695dbeb5ab8e862d8f70117976ed24))
* **Validator:** 增加 StringVal 规则 ([8c6a120](https://github.com/twinh/wei/commit/8c6a1205deb9a2cc90c15a7d5d67c75d2c5be199))
* **Validator:** 增加 UNumber 表示大于 0 的Number ([0857e5e](https://github.com/twinh/wei/commit/0857e5e3541120f8da832f5201eaf1127b445424))
* **Validator:** 增加大小判断规则的简写：Gt、Gte、Lt 和 Lte ([a0d68e7](https://github.com/twinh/wei/commit/a0d68e7a8217dc2ab4c4049534784fe0ff718bc1))
* **Validator:** 增加方法以获取校验通过的数据 ([47d985c](https://github.com/twinh/wei/commit/47d985cea6a44500bbfc534771d33f9a04a8265c))
* **Validator:** 校验的键名可以传入数组，表示校验相应数据路径的数据 ([bac0a23](https://github.com/twinh/wei/commit/bac0a23e142cce21a88b85158c0c968cf6598683))
* 增加 getCurrentField 和 hasField 方法 ([e1e5169](https://github.com/twinh/wei/commit/e1e5169b01793e996528d016a6f06428db3a2ef3))
* 为数值类型增加 `u` 开头的方法来表示无符号，例如 `uInt`，`uDecimal`，移除 `autoUnsigned` 选项，改为明确指定是否有符号 ([f964fc1](https://github.com/twinh/wei/commit/f964fc1dfc7431d500271a0ef731fc0a3658d507))


### Code Refactoring

* **IsChar:** 更改 `IsCharLength` 为 `IsChar` ([41853b4](https://github.com/twinh/wei/commit/41853b4858bbb3ef8ab51f37e722e95fbd29c488))
* **Schema:** 移除废弃的 `timestampsV1`, `userstampsV1` 和 `softDeletableV1` 方法，直接使用 `timestamps`, `userstamps` 和 `softDeletable` ([0202591](https://github.com/twinh/wei/commit/0202591034e81a0eb3ce301104034fdb949e07b6))
* **Validator:** 基础类型校验器移除 Val 后缀 ([1a49d63](https://github.com/twinh/wei/commit/1a49d63c0edc2212eb10b79e2a525db4d8e095f0))
* **Validator:** 校验规则类移除 Validator 命名空间，增加 Is 前缀 ([eb8a2ef](https://github.com/twinh/wei/commit/eb8a2ef6f12a6e53fdd8e36eb3c591f0ff30349e))
* **Schema:** `date`, `datetime`, `timestamp` 类型的默认值改为 `NULL`，匹配 MySQL 5.7+ 的默认模式 ([659e6d4](https://github.com/twinh/wei/commit/659e6d4ece3f1c3ff0c04e5f4642d29c14b8c775))
* **IsDateTime:** `IsDateTime` 改名为 `IsAnyDateTime`，增加 `IsDateTime` 校验格式为 `Y-m-d H:i:s` 的日期 ([d7d4ccb](https://github.com/twinh/wei/commit/d7d4ccb627b69da87a3abf2a3a9ea337f0f2eb83))


### BREAKING CHANGES

* **IsEach:** `V::getValidator` 方法移除 `$data` 参数
`IsEach::getValidatorOptions` 方法增加 `$data` 参数
* 为数值类型增加 `u` 开头的方法来表示无符号，例如 `uInt`，`uDecimal`，移除 `autoUnsigned` 选项，改为明确指定是否有符号
* **Schema:** `date`, `datetime`, `timestamp` 类型的默认值改为 `NULL`，匹配 MySQL 5.7+ 的默认模式
* `IsDateTime` 改名为 `IsAnyDateTime`，增加 `IsDateTime` 校验格式为 `Y-m-d H:i:s` 的日期
* **Schema:** 移除废弃的 `timestampsV1`, `userstampsV1` 和 `softDeletableV1` 方法，直接使用 `timestamps`, `userstamps` 和 `softDeletable`
* **V:** 基础类型不再可以作为方法附加到已有的校验中，需直接调用 `addRule($type)`
* **IsChar:** 更改 `IsCharLength` 为 `IsChar`
* **Validator:** 基础类型校验器移除 Val 后缀
* **Validator:** 校验规则类移除 Validator 命名空间，增加 Is 前缀
* **Validator:** Number 规则传入 NAN 将返回 false
* 校验数组或对象数据时，如果数据中包含要校验的键名，则认为值是存在的

## [0.9.31](https://github.com/twinh/wei/compare/v0.9.30...v0.9.31) (2020-09-25)


### Bug Fixes

* **V:** 链接验证服务每次调用都应创建新的对象 ([1129420](https://github.com/twinh/wei/commit/11294200b5c07c0cd4f9c8a83f43177a80e27759))


### Features

* **Pinyin:** 获取拼音可以设置分隔符 ([1a35a7b](https://github.com/twinh/wei/commit/1a35a7b42683966b33e211905d50eb807f6d2477))





### Dependencies

* **@miaoxing/dev:** upgrade from 6.1.1 to 6.1.2

## [0.9.30](https://github.com/twinh/wei/compare/v0.9.29...v0.9.30) (2020-08-17)





### Dependencies

* **@miaoxing/dev:** upgrade from 6.1.0 to 6.1.1

## [0.9.29](https://github.com/twinh/wei/compare/v0.9.28...v0.9.29) (2020-08-14)





### Dependencies

* **@miaoxing/dev:** upgrade from 6.0.0 to 6.1.0

## [0.9.28](https://github.com/twinh/wei/compare/v0.9.27...v0.9.28) (2020-08-14)





### Dependencies

* **@miaoxing/dev:** upgrade from  to 0.1.0

## [0.9.27](https://github.com/twinh/wei/compare/v0.9.26...v0.9.27) (2020-08-07)


### Bug Fixes

* **error:** 增加处理 Throwable 错误 ([6d59a86](https://github.com/twinh/wei/commit/6d59a86e5011e140619b0eaecb02db27f2a3bd40))

## [0.9.26](https://github.com/twinh/wei/compare/v0.9.25...v0.9.26) (2020-08-06)


### Features

* 增加 Req， Res 服务，废弃原来的 Request，Response ([1a6a7c3](https://github.com/twinh/wei/commit/1a6a7c36266604d423c568c556594541ece9e4d9))

## [0.9.25](https://github.com/twinh/wei/compare/v0.9.24...v0.9.25) (2020-08-01)


### Features

* Share 服务加入 wei 中 ([c881063](https://github.com/twinh/wei/commit/c8810633cb6a96f16ae775407da5ec6a99520bc0))


### Reverts

* "test: 设置 driver 后还原" ([93be887](https://github.com/twinh/wei/commit/93be887c9c98c49524e6d658937796e7b6d2fc72))

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
