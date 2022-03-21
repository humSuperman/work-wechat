# work-wechat
## 环境需求

- PHP >= 7.0.0
- [Composer](https://getcomposer.org/) >= 2.0

## 安装

```bash
composer require midi/work-wechat
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
