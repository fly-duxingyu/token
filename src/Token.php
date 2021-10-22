<?php

namespace Duxingyu\Token;

use Cache;
use Psr\SimpleCache\InvalidArgumentException;

class Token
{
    private static $tokenObj;

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
     * @return string
     * @throws InvalidArgumentException
     */
    public function generateToken($user)
    {
        $config = config('tokenConfig');
        $user = is_string($user) ? $user : json_encode($user, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
        $suffix = !empty($config['token_prefix']) ? $config['token_prefix'] : 'robertvivi';
        $token = sha1(md5($user . mt_rand(1, 100000) . uniqid() . $suffix));
        $expiration_time = !empty($config['expiration_time']) ? $config['expiration_time'] : 86400;
        Cache::set($token, $user, $expiration_time);
        return $token;
    }

    /**
     * 删除token
     * @param $token_key
     * @throws InvalidArgumentException
     */
    public function deleteToken($token_key)
    {
        Cache::delete($token_key);
    }

    /**
     * token中取用户信息
     * @param $token_key
     * @return array|mixed
     */
    public function getValue($token_key)
    {
        if (Cache::has($token_key)) {
            $data = Cache::get($token_key);
            return json_decode($data, true);
        }
        return [];
    }

    /**
     * 验证token是否存在
     * @param $token_key
     * @return false
     */
    public function validatorToken($token_key)
    {
        if (Cache::has($token_key)) {
            return true;
        } else {
            return false;
        }
    }
}
