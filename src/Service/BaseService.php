<?php

namespace WorkWechat\Service;

use Psr\SimpleCache\CacheInterface;
use WorkWechat\Utils\FileCache;

class BaseService
{
    protected $client,$cacheKey;
    private $accessToken;
    /**
     * @var CacheInterface
     */
    private $cacheObject;

    public function __construct()
    {
        $this->client = new HttpClientService();
    }

    public function setAccessToken(string $token)
    {
        $this->accessToken = $token;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getCacheObject(): CacheInterface
    {
        if(empty($this->cacheObject)){
            return new FileCache();
        }
        return $this->cacheObject;
    }

    public function setCacheObject(CacheInterface $cacheObject)
    {
        $this->cacheObject = $cacheObject;
    }
}