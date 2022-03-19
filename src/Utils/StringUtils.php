<?php

namespace WorkWechat\Utils;

class StringUtils
{
    public static function signature(string $ticket, string $str, int $time, string $url): string
    {
        return sha1('jsapi_ticket=' . $ticket . '&noncestr=' . $str . '&timestamp=' . $time . '&url=' . $url);
    }

    public static function rand($num = 10): string
    {
        $s = '23456789qwertyuipPasdfghjkzxcvbnmQWERTYUPASDFGHJKLZXCVBNM';
        $len = strlen($s) - 1;
        $r = '';
        for ($i = 0; $i < $num; $i++) {
            $r .= $s[rand(0, $len)];
        }
        return $r;
    }
}