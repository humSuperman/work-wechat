<?php

namespace WorkWechat\Tests\Service;

use PHPUnit\Framework\TestCase;
use WorkWechat\Service\TagService;
use WorkWechat\Utils\WorkWechatException;

class TagServiceTest extends TestCase
{

    private $tagService;
    public function setUp()
    {
        parent::setUp();
        $this->tagService = new TagService();
        $this->tagService->setAccessToken('91NW76ZQw');
    }

    /**
     * @throws WorkWechatException
     */
    public function testTagList(){
        $resp = $this->tagService->list();
        $this->assertArrayHasKey('taglist',$resp);
    }

    public function testTagCreate(){
        $resp = $this->tagService->create('test-tag');
        $this->assertArrayHasKey('tagid',$resp);
    }
    public function testTagUpdate(){
        $resp = $this->tagService->update(4,'test-tag11');
        $this->assertEquals('updated',$resp['errmsg']);
    }
    public function testTagDelete(){
        $resp = $this->tagService->create('test-tag');
        $resp = $this->tagService->delete($resp['tagid']);
        $this->assertEquals('deleted',$resp['errmsg']);
    }

    public function testBindDepartment(){
        $resp = $this->tagService->bindDepartment(4,1,2,3,4,54,6);
        $this->assertEquals('deleted',$resp['errmsg']);
    }


}
