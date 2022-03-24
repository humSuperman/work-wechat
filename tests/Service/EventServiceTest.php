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
}
