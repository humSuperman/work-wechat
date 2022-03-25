<?php

namespace WorkWechat\Tests\Service;

use WorkWechat\Service\BaseService;
use WorkWechat\Service\MessageService;
use PHPUnit\Framework\TestCase;
use WorkWechat\Utils\WorkWechatException;

class MessageServiceTest extends TestCase
{

    private $messageService,$baseService;
    public function setUp()
    {
        parent::setUp();
        $this->messageService = new MessageService('1000012');
        $this->baseService = new BaseService();
    }

    public function testSendTextMsgToCompanyUser()
    {
        $token = 'GiBvLa7DJ9ZaG5XouhNeESbfIrcv7B3rjp52FuloaRWubEVqmUbJ7AZfiURJkvkGQG56EHBkI607R84_V0XPLUy7y5Aq-DEg73dNH7pVg0ouhjHtq0-msNuBYyHkUh1bZMcBaVgAv7_hJ7W7fl-eW5QTzfQrr8fQ5X1jhQXNZYLMClmRKEF2cfAIPXgdmrZzn58ePXgalVBfe3t1G9SamA';
        $this->messageService->setAccessToken($token);
        $this->messageService->setMessage('test-message');
        $res = $this->messageService->sendTextMsgToCompanyUser('13838384388');
        $this->assertEquals('ok',$res['errmsg']);
    }

    public function testSendTextMsgToCompanyUserFail()
    {
        $this->expectException(WorkWechatException::class);
        $token = 'GA';
        $this->messageService->setAccessToken($token);
        $this->messageService->setMessage('test-message');
        $this->messageService->sendTextMsgToCompanyUser('13838384388');
    }
}
