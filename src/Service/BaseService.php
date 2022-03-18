<?php

namespace WorkWechat\Service;

class BaseService
{
    protected $client;
    private $accessToken;

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
}