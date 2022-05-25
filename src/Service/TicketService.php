<?php

namespace WorkWechat\Service;

use Carbon\Carbon;
use WorkWechat\Utils\Cache;
use WorkWechat\Utils\WorkWechatException;

class TicketService extends BaseService
{
    const COMPANY_TICKET = 'company_ticket';
    const AGENT_TICKET = 'agent_ticket';

    const COMPANY_JSAPI_TICKET = '/cgi-bin/get_jsapi_ticket';
    const AGENT_JSAPI_TICKET = '/cgi-bin/ticket/get';
    const CORPID_TO_OPEN_CORPID = '/cgi-bin/service/corpid_to_opencorpid';

    private $cache;

    public function __construct(string $corpId, string $secret)
    {
        parent::__construct();
        $this->cache = new Cache($corpId, $secret);
    }

    public function company(): string
    {
        try {
            //  获取缓存
            return $this->cache->get(self::COMPANY_TICKET);
        } catch (WorkWechatException $e) {
            //  缓存不存在、过期，刷新
            return $this->refreshCompany();
        }
    }

    public function refreshCompany(): string
    {
        $this->client->setQuery([
            'access_token' => $this->getAccessToken()
        ]);
        $data = $this->client->get(self::COMPANY_JSAPI_TICKET);
        $this->cache->set(self::COMPANY_TICKET, $data['ticket'], Carbon::now()->addMinutes(110));
        return $data['ticket'];
    }

    public function agent(): string
    {
        try {
            //  获取缓存
            return $this->cache->get(self::AGENT_TICKET);
        } catch (WorkWechatException $e) {
            //  缓存不存在、过期，刷新
            return $this->refreshAgent();
        }
    }

    public function refreshAgent(): string
    {
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
            'type' => 'agent_config'
        ]);
        $data = $this->client->get(self::AGENT_JSAPI_TICKET);
        $this->cache->set(self::AGENT_TICKET, $data['ticket'], Carbon::now()->addMinutes(110));
        return $data['ticket'];
    }

    public function openCorpId(string $corpId): string
    {
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
        ]);
        $this->client->setJson([
            'corpid' => $corpId,
        ]);
        $data = $this->client->post(self::CORPID_TO_OPEN_CORPID);
        $this->cache->set(self::AGENT_TICKET, $data['ticket'], Carbon::now()->addMinutes(110));
        return $data['open_corpid'];
    }
}