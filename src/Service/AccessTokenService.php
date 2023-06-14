<?php

namespace WorkWechat\Service;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use WorkWechat\Utils\FileCache;
use WorkWechat\Utils\WorkWechatException;

class AccessTokenService extends BaseService
{
    const ACCESS_TOKEN = 'access_token';
    const GET_TOKEN = '/cgi-bin/gettoken';

    private $cache, $corpId = '', $secret = '';

    public function __construct(string $corpId, string $secret)
    {
        parent::__construct();
        $this->corpId = $corpId;
        $this->secret = $secret;
        $this->cache = new FileCache($corpId, $secret);
    }

    /**
     * @throws WorkWechatException
     */
    public function getToken(): string
    {
        try {
            //  获取缓存
            return $this->cache->get(self::ACCESS_TOKEN);
        } catch (WorkWechatException $e) {
            //  缓存不存在、过期，刷新
            return $this->refresh();
        }
    }

    /**
     * @throws WorkWechatException
     */
    public function refresh(): string
    {
        $this->client->setQuery([
            'corpid' => $this->corpId,
            'corpsecret' => $this->secret,
        ]);
        $data = $this->client->get(self::GET_TOKEN);
        //  更新缓存
        $this->cache->set(self::ACCESS_TOKEN, $data['access_token'], Carbon::now()->addMinutes(110));
        return $data['access_token'];
    }
}