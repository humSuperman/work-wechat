<?php

namespace WorkWechat\Utils;

class Prpcrypt
{
    public $key = null;
    public $iv = null;

    /**
     * Prpcrypt constructor.
     * @param $k
     */
    public function __construct($k)
    {
        $this->key = base64_decode($k . '=');
        $this->iv = substr($this->key, 0, 16);

    }

    /**
     * 加密
     * @param string $text
     * @param string $receiveId
     * @return string
     * @throws WorkWechatException
     */
    public function encrypt(string $text,string $receiveId): string
    {
        try {
            //拼接
            $text = $this->getRandomStr() . pack('N', strlen($text)) . $text . $receiveId;
            //添加PKCS#7填充
            $pkcEncoder = new PKCS7Encoder();
            $text = $pkcEncoder->encode($text);
            //加密
            if (function_exists('openssl_encrypt')) {
                $encrypted = openssl_encrypt($text, 'AES-256-CBC', $this->key, OPENSSL_ZERO_PADDING, $this->iv);
            } else {
                $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, base64_decode($text), MCRYPT_MODE_CBC, $this->iv);
            }
            return $encrypted;
        } catch (\Exception $e) {
            print($e);
            throw new WorkWechatException('aes 加密失败');
        }
    }

    /**
     * 解密
     *
     * @param string $encrypted
     * @param string $corpId
     * @return string
     * @throws WorkWechatException
     */
    public function decrypt(string $encrypted,string $corpId) : string
    {

        $decrypted = $this->aesDecrypt($encrypted);
        try {
            //删除PKCS#7填充
            $pkcEncoder = new PKCS7Encoder();
            $result = $pkcEncoder->decode($decrypted);
            if (strlen($result) < 16) {
                throw new WorkWechatException('aes 解密失败');
            }
            //拆分
            $content = substr($result, 16, strlen($result));
            $lenList = unpack('N', substr($content, 0, 4));
            $xmlLen = $lenList[1];
            $xmlContent = substr($content, 4, $xmlLen);
            $fromReceiveId = substr($content, $xmlLen + 4);
        } catch (\Exception $e) {
            print $e;
            throw new WorkWechatException('解密后得到的buffer非法');
        }
        if ($fromReceiveId != $corpId) {
            throw new WorkWechatException('corpId 校验失败');
        }
        return $xmlContent;
    }

    /**
     * @throws WorkWechatException
     */
    private function aesDecrypt(string $encrypted){
        try {
            //解密
            if (function_exists('openssl_decrypt')) {
                return openssl_decrypt($encrypted, 'AES-256-CBC', $this->key, OPENSSL_ZERO_PADDING, $this->iv);
            } else {
                return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, base64_decode($encrypted), MCRYPT_MODE_CBC, $this->iv);
            }
        } catch (\Exception $e) {
            throw new WorkWechatException('aes 解密失败');
        }
    }

    /**
     * 生成随机字符串
     *
     * @return string
     */
    private function getRandomStr(): string
    {
        $str = '';
        $s = '0123456789qwertyuiopaasdfghjklzxcvbnmQWERTYUIOPAASDFGHJKLZXCVBNM';
        $max = strlen($s) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $s[mt_rand(0, $max)];
        }
        return $str;
    }
}
