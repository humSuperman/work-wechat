<?php

namespace WorkWechat\Service;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use WorkWechat\Utils\Cache;
use WorkWechat\Utils\WorkWechatException;

class AccessTokenService extends BaseService
{
    const ACCESS_TOKEN = 'access_token';
    private $cache, $corpId = '', $secret = '';

    const GET_TOKEN = '/cgi-bin/gettoken';

    public function __construct(string $corpId, string $secret)
    {
        parent::__construct();
        $this->corpId = $corpId;
        $this->secret = $secret;
        $this->cache = new Cache($corpId, $secret);
    }

    /**
     * @throws \HttpException
     * @throws WorkWechatException
     * @throws GuzzleException
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
     * @throws \HttpException
     * @throws GuzzleException
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