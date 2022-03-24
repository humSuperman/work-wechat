<?php

namespace WorkWechat\Tests\Service;

use WorkWechat\Service\EventService;
use PHPUnit\Framework\TestCase;
use WorkWechat\Utils\WorkWechatException;

class EventServiceTest extends TestCase
{
    private $service;

    public function setUp()
    {
        parent::setUp();
        $this->service = new EventService('wx5823bf96d3bd56c7', 'jWmYm7qr5nMoAUwZRjGtBxmz3KA1tkAj3ykkR6q2B2C', 'QDG6eK');
    }


    public function testVerifyURL()
    {
        $this->assertEquals('1616140317555161061',$this->service->verifyURL('5c45ff5e21c57e6ad56bac8758b79b1d9ac89fd3', '1409659589', '263014780',
            'P9nAzCzyDtyTWESHep1vC5X9xho/qYX3Zpb4yKa9SKld1DsH3Iyt3tP3zNdtp+4RPcs8TgAE7OaBO+FZXvnaqQ=='));

    }


    /**
     * @expectedException        WorkWechatException
     * @expectedExceptionMessage 签名验证错误
     */
    public function testVerifyURLSignFail()
    {
        $this->expectException(WorkWechatException::class);
        $this->service->verifyURL('5c45ff5e21c57e6ad56bac8758b79b1d9ac89fd3', '1409659589', '263014780',
            '12345');

    }

    public function testDecryptMsg()
    {
        $msg = $this->service->decryptMsg('477715d11cdb4164915debcba66cb864d751f3e6', '1409659813', '1372623149',
            "<xml><ToUserName><![CDATA[wx5823bf96d3bd56c7]]></ToUserName><Encrypt><![CDATA[RypEvHKD8QQKFhvQ6QleEB4J58tiPdvo+rtK1I9qca6aM/wvqnLSV5zEPeusUiX5L5X/0lWfrf0QADHHhGd3QczcdCUpj911L3vg3W/sYYvuJTs3TUUkSUXxaccAS0qhxchrRYt66wiSpGLYL42aM6A8dTT+6k4aSknmPj48kzJs8qLjvd4Xgpue06DOdnLxAUHzM6+kDZ+HMZfJYuR+LtwGc2hgf5gsijff0ekUNXZiqATP7PF5mZxZ3Izoun1s4zG4LUMnvw2r+KqCKIw+3IQH03v+BCA9nMELNqbSf6tiWSrXJB3LAVGUcallcrw8V2t9EL4EhzJWrQUax5wLVMNS0+rUPA3k22Ncx4XXZS9o0MBH27Bo6BpNelZpS+/uh9KsNlY6bHCmJU9p8g7m3fVKn28H3KDYA5Pl/T8Z1ptDAVe0lXdQ2YoyyH2uyPIGHBZZIs2pDBS8R07+qN+E7Q==]]></Encrypt><AgentID><![CDATA[218]]></AgentID></xml>");
        $this->assertArrayHasKey('MsgId',$msg);
    }

    /**
     * @expectedException        WorkWechatException
     * @expectedExceptionMessage 签名验证错误
     */
    public function testDecryptMsgSignFail()
    {
        $this->expectException(WorkWechatException::class);
        $this->service->decryptMsg('477715d11cdb4164915debcba66cb864d751f3e6', '1409659813', '1372623149',
            "<xml><ToUserName><![CDATA[wx5823bf96d3bd56c7]]></ToUserName><Encrypt><![CDATA[RypEvHKD8QQKFhvQ6QleEB4J58tiPdvo+rtK1I9qca6aM/wvqnLSV5zEPeusUiX5L5X/0lWfrf0QADHHhGd3QczcdCUpj911L3vg3W/sYYvuJTs3TUUkSUXxaccAS0qhxchrRYt66wiSpGLYL42aM6A8dTT+6k4aSknmPj48kzJs8qLjvd4Xgpue06DOdnLxAUHzM6+kDZ+HMZfJYuR+LtwGc2hgf5gsijff0ekUNXZiqATP7PF5mZxZ3Izoun1s4zG4LUMnvw2r+KqCKIw+3IQH03v+BCA9nMELNqbSf6tiWSrXJB3LAVGUcallcrw8V2t9EL4EhzJWrQUax5wLVMNS0+rUPA3k22Ncx4XXZS9o0MBH27Bo6BpNelZpS+/uh9KsNlY6bHCmJU9p8g7m3fVKn28H3KDYA5Pl/T8Z1ptDAVe0lXdQ2Yoyy]]></Encrypt><AgentID><![CDATA[218]]></AgentID></xml>");
    }
}
