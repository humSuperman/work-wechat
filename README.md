# work-wechat
## 环境需求

- PHP >= 7.0.0
- [Composer](https://getcomposer.org/) >= 2.0

## 安装

```bash
composer require midi/work-wechat
```
#### [验证URL有效性](https://developer.work.weixin.qq.com/document/path/90238#%E9%AA%8C%E8%AF%81url%E6%9C%89%E6%95%88%E6%80%A7)
```php
$service = new WorkWechat\EventService('corpId','encodingAesKey','token');
try{
    // 需要自行urldecode
    echo $service->verifyURL('msgSignature',(int)'timestamp','nonce','msgEncrypt');
}catch (\Exception $e){
    print $e;
    echo '';
}
```

#### [接收post业务数据](https://developer.work.weixin.qq.com/document/path/91116#32-%E6%94%AF%E6%8C%81http-post%E8%AF%B7%E6%B1%82%E6%8E%A5%E6%94%B6%E4%B8%9A%E5%8A%A1%E6%95%B0%E6%8D%AE)
```php
$service = new WorkWechat\EventService('corpId','encodingAesKey','token');
$xml = file_get_contents("php://input");
try{
    // 需要自行urldecode
    // 接收到的数据已转为array
    $service->decryptMsg('msgSignature',(int)'timestamp','nonce',$xml);
}catch (\Exception $e){
    print $e;
    echo 'fail';
}
```

#### [网页授权](https://developer.work.weixin.qq.com/document/path/91022)
```php
$url = WorkWechat\AuthService::getRedirectUrl('corpId','https://www.baidu.com')
```

#### [扫码登录](https://developer.work.weixin.qq.com/document/path/91019)
```php
$url = WorkWechat\AuthService::getQrCodeUrl('corpId','agentId','https://www.baidu.com','')
```

#### [获取access_token](https://developer.work.weixin.qq.com/document/path/91039)
```php
// 已增加本地文件缓存
$service = new WorkWechat\AccessTokenService('corpId','secret');
$accessToken = $service->getToken();
// 刷新access_token
$accessToken = $service->refresh();

```

#### [获取访问用户身份](https://developer.work.weixin.qq.com/document/path/91023)
```php
$service = new WorkWechat\UserService();
$service->setAccessToken($accessToken);
$service->companyUserByCode('code');
```
#### [读取成员](https://developer.work.weixin.qq.com/document/path/90196)
```php
$userService = new WorkWechat\UserService();
$userService->setAccessToken($accessToken);
$userService->detail('userId')
```

#### [获取客户详情](https://developer.work.weixin.qq.com/document/path/92114)
```php
$customerService = new WorkWechat\CustomerService();
$customerService->setAccessToken($accessToken);
$customerService->customerDetail('externalUserid')
```

#### [联系客户统计](https://developer.work.weixin.qq.com/document/path/92132)
```php
$customerService = new WorkWechat\CustomerService();
$customerService->setAccessToken($accessToken);
$customerService->userBehaviorData(['userid'],['deptmentId'],1648780712,1649903913)
```

#### [企业部门]()
```php
$departmentService = new WorkWechat\DepartmentService();
$departmentService->setAccessToken($accessToken);
// 获取所有部门
$departmentService->list();
// 获取某个部门及下级部门
$departmentService->list(id);
// 部门详情
$departmentService->detail(id);

// 所有部门及部门下属员工
$departmentService->totalUserList();

// 所有部门及部门下属员工 树形结构
$departmentService->departmentTreeANdTotalUser();
```

#### [发送文本消息](https://developer.work.weixin.qq.com/document/path/90236#%E6%96%87%E6%9C%AC%E6%B6%88%E6%81%AF)
```php
$messageService = new WorkWechat\MessageService('agentId');
$messageService->setAccessToken($accessToken);
$messageService->setMessage('text-message'); // 设置发送的文本消息
$messageService->setSecrecy(1); // 设置消息是否保密 0-否 1-是
$messageService->sendTextMsgToCompanyUser('userId','userId','userId','userId','userId','userId','userId'); // 向多个/一个userId发送此消息
```

#### 如何获取external_userid
[通过成员userId获取](https://developer.work.weixin.qq.com/document/path/92113)
[前端jsapi获取](https://developer.work.weixin.qq.com/document/path/91799)

#### 获取企业jsapi ticket
[wx.config](https://developer.work.weixin.qq.com/document/path/94313)
```php
$service = new WorkWechat\TicketService('corpId','secret');
$service->setAccessToken($accessToken);
$service->company();
// 刷新企业jsapi ticket
$service->refreshCompany()
```

#### 获取应用jsapi ticket
[wx.agentConfig](https://developer.work.weixin.qq.com/document/path/94313)
```php
$service = new WorkWechat\TicketService('corpId','secret');
$service->setAccessToken($accessToken);
$service->agent();
// 刷新应用jsapi ticket
$service->refreshCompany()
```

#### 明文corpid转换为加密corpid
[明文corpid转换为加密corpid](https://developer.work.weixin.qq.com/document/path/95604)
```php
$service = new WorkWechat\TicketService('corpId','secret');
$service->setAccessToken($accessToken);
$service->openCorpId("corpid");
```
