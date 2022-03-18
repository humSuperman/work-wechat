# work-wechat
## 环境需求

- PHP >= 8.0.2
- [Composer](https://getcomposer.org/) >= 2.0

## 安装

```bash
composer require midi/work-wechat
```

#### 获取access_token，已增加本地文件缓存

```php
$service = new WorkWechat\AccessTokenService('corpId','secret');
$accessToken = $service->getToken();
```

#### 刷新access_token，已增加本地文件缓存

```php
$service = new WorkWechat\AccessTokenService('corpId','secret');
$accessToken = $service->refresh();
```
#### code兑换员工信息

```php
$service = new WorkWechat\UserService();
$service->setAccessToken($accessToken);
$service->companyUserByCode('code');
```
