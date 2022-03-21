<?php

namespace WorkWechat\Service;

class AuthService extends BaseService
{
    public static function getRedirectUrl(string $corpId, string $url): string
    {
        return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $corpId . '&redirect_uri=' .
            urlencode($url) . '&response_type=code&scope=snsapi_base&state=#wechat_redirect';
    }

    public static function getQrCodeUrl(string $corpId, string $agentId, string $url, string $state = ''): string
    {
        return 'https://open.work.weixin.qq.com/wwopen/sso/qrConnect?appid=' . $corpId . '&agentid=' . $agentId .
            '&redirect_uri=' . urlencode($url) . '&state=' . $state;
    }
}