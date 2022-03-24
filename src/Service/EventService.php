<?php

namespace WorkWechat\Service;

use WorkWechat\Utils\Prpcrypt;
use WorkWechat\Utils\WorkWechatException;

class EventService
{
    private $corpId, $encodingAesKey, $token;
    public function __construct(string $corpId, string $encodingAesKey, string $token)
    {
        $this->corpId = $corpId;
        $this->encodingAesKey = $encodingAesKey;
        $this->token = $token;
    }

    /**
     * @param string $msgSignature 消息签名
     * @param int $timestamp 时间戳
     * @param string $nonce 随机数字串
     * @param string $msgEncrypt 加密字符串
     * @return string string
     * @throws WorkWechatException
     */
    public function verifyURL(string $msgSignature,int $timestamp,string $nonce,string $msgEncrypt): string
    {
        $sign = $this->getMsgSignature($this->token,$timestamp, $nonce, $msgEncrypt);
        if($sign != $msgSignature){
            throw new WorkWechatException('签名验证错误');
        }
        $prpcrypt = new Prpcrypt($this->encodingAesKey);
        return $prpcrypt->decrypt($msgEncrypt,$this->corpId);
    }

    private function getMsgSignature($token, $timestamp, $nonce, $msgEncrypt): string
    {
        $array = [$token, $timestamp, $nonce, $msgEncrypt];
        sort($array, SORT_STRING);
        $str = implode($array);
        return sha1($str);
    }
}