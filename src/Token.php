<?php

namespace Duxingyu\Token;

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
     * @param bool $is_json
     * @return string
     */
    public function generateToken($user, $is_json = false)
    {
        $user = $is_json ? $user : json_encode($user);
        $token = sha1(md5($user . mt_rand(1, 100000) . uniqid() . 'robertvivi'));
        session()->put($token, $user);
        return $token;
    }

    /**
     * 删除session
     * @param $token_key
     */
    public function deleteToken($token_key)
    {
        session()->forget($token_key);
    }

    /**
     * token中取用户信息
     * @param $token_key
     * @return array|mixed
     */
    public function getValue($token_key)
    {
        if (session()->exists($token_key)) {
            $data = session()->get($token_key);
            return json_decode($data, true);
        }
        return [];
    }

    /**
     * 验证session是否存在
     * @param $token_key
     * @return false
     */
    public function validatorToken($token_key)
    {
        if (session()->exists($token_key)) {
            $data = session()->get($token_key);
            $this->generateToken($data, true);
            return true;
        } else {
            return false;
        }
    }
}
