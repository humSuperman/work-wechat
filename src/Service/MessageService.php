<?php

namespace WorkWechat\Service;

use WorkWechat\Utils\WorkWechatException;

class MessageService extends BaseService
{
    const APPLET_MESSAGE = '/cgi-bin/message/send';

    private $agentId, $message;
    private $secrecy = 0;

    public function __construct(string $agentId)
    {
        parent::__construct();
        $this->agentId = $agentId;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function setSecrecy(string $secrecy)
    {
        // 是否是保密消息 0-可对外分享 1-不能分享且内容显示水印
        $this->secrecy = $secrecy;
    }

    /**
     * @throws WorkWechatException
     */
    public function sendTextMsgToCompanyUser(string $id): array
    {
        $json = [
            'touser' => $id,
            'msgtype' => 'text',
            'agentid' => $this->agentId,
            'text' => [
                'content' => $this->message,
            ],
            'safe' => $this->secrecy,
        ];
        $query = [
            'access_token' => $this->getAccessToken(),
        ];
        $this->client->setQuery($query);
        $this->client->setJson($json);
        return $this->client->post(self::APPLET_MESSAGE);
    }

    public function sendTextMsgToCompanyUsers(array $users): array
    {
        $json = [
            'touser' => implode('|', $users),
            'msgtype' => 'text',
            'agentid' => $this->agentId,
            'text' => [
                'content' => $this->message,
            ],
            'safe' => $this->secrecy,
        ];
        $query = [
            'access_token' => $this->getAccessToken(),
        ];
        $this->client->setQuery($query);
        $this->client->setJson($json);
        return $this->client->post(self::APPLET_MESSAGE);
    }
}