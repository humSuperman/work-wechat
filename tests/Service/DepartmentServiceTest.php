<?php

namespace WorkWechat\Tests\Service;

use WorkWechat\Service\AccessTokenService;
use WorkWechat\Service\BaseService;
use WorkWechat\Service\DepartmentService;
use PHPUnit\Framework\TestCase;

class DepartmentServiceTest extends TestCase
{

    private $departmentService,$baseService;
    public function setUp()
    {
        parent::setUp();
        $this->departmentService = new DepartmentService();
        $this->baseService = new BaseService();
    }

    public function testTotalUserList()
    {
        $token = "HviZpaxfVLnVxkW2vN63N9J1NjzSx_dfMMzU5I3VBw";
        $this->departmentService->setAccessToken($token);
        echo json_encode($this->departmentService->totalUserList());
    }
}
