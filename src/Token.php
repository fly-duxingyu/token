<?php

namespace Duxingyu\Token;

use Redis;

class Token
{
    private static $tokenObj;
    /**
     * @var Redis
     */
    private $redis;

    public static function init()
    {
        if (!self::$tokenObj instanceof self) {
            self::$tokenObj = new self();
        }
        return self::$tokenObj;
    }

    private function __construct()
    {

    }

    private function __destruct()
    {
    }

    /**
     * 生成token
     * @param $user
     * @param bool $is_json
     * @return string
     */
    public function generateToken($user, $is_json = false)
    {
        $time = config('tokenConfig.time_out') ?: 300;
        $user = $is_json ? $user : json_encode($user);
        $token = sha1(md5($user . mt_rand(1, 100000) . uniqid() . config('tokenConfig.token_prefix') ?: 'robertvivi'));
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->setex($token, $time, $user);
        $redis->close();
        return $token;
    }

    /**
     * 删除session
     * @param $token_key
     */
    public function deleteToken($token_key)
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        if ($redis->exists($token_key)) {
            $redis->del($token_key);
        };
        $redis->close();
    }

    /**
     * token中取用户信息
     * @param $token_key
     * @return array|mixed
     */
    public function getValue($token_key)
    {
        $data = [];
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);

        if ($redis->exists($token_key)) {
            $data = json_decode($redis->get($token_key), true);
        }
        $redis->close();
        return $data;
    }

    /**
     * 验证session是否存在
     * @param $token_key
     * @return bool|int
     */
    public function validatorToken($token_key)
    {
        $time = config('tokenConfig.time_out') ?: 300;
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $bool = $redis->exists($token_key);
        if ($bool) {
            $redis->setex($token_key, $time, $redis->get($token_key));
        }
        $redis->close();
        return $bool;
    }
}
