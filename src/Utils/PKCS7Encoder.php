<?php

namespace WorkWechat\Utils;

class PKCS7Encoder
{
    const BLOCK_SIZE = 32;

    /**
     * 对需要加密的明文进行填充补位
     * @param string $text 需要进行填充补位操作的明文
     * @return 补齐明文字符串
     */
    function encode(string $text): string
    {
        $textLength = strlen($text);
        //计算需要填充的位数
        $amountToPad = self::BLOCK_SIZE - ($textLength % self::BLOCK_SIZE);
        if ($amountToPad == 0) {
            $amountToPad = self::BLOCK_SIZE;
        }
        //获得补位所用的字符
        $padChr = chr($amountToPad);
        $tmp = str_repeat($padChr, $amountToPad);
        return $text . $tmp;
    }

    /**
     * 对解密后的明文进行补位删除
     * @param string $text 解密后的明文
     * @return 删除填充补位后的明文
     */
    function decode(string $text): string
    {

        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > self::BLOCK_SIZE) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

}
