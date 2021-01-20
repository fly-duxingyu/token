# token

#### 介绍
用于登陆生成token 验证token 删除token 
#####
首先配置生成token的key:例如
###
在config目录下创建tokenConfig.php文件
#####
```
<?php
 return [
     'token_prefix' => 'robertvivi'//加密token字符串
 ];
```
不配置默认使用 'robertvivi' 加密
#### 使用方法
```
 //生成token 传递参数值会以json格式保存至session
 Token::init()->generateToken(['name'=>'张三','id'=>1]);
 //删除token,用于退出登陆
 Token::init()->deleteToken('c2rFIU3ym8AXJ1aU');
 //通过token获取存入的值
 Token::init()->getValue('c2rFIU3ym8AXJ1aU');
 //验证token
 Token::init()->validatorToken('c2rFIU3ym8AXJ1aU');
```
####
 使用中间件
 在 app/Http/Kernel.php 中配置中间件:例如配置在api分组中
 ```
protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            Token::class
        ],
    ];
```
