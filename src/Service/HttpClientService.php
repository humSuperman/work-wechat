<?php

namespace WorkWechat\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use WorkWechat\Utils\WorkWechatException;

class HttpClientService
{
    private $client, $response;

    private $option = [
        'query' => [],
        'multipart' => [],
        'json' => [],
        'headers' => [],
    ];

    public function __construct()
    {
        $this->client = new Client($this->config());
    }

    public function config(): array
    {
        // todo 动态配置
        return [
            'base_uri' => 'https://qyapi.weixin.qq.com',
            'timeout' => 10,
        ];
    }

    public function setHeaders(array $headers = [])
    {
        $this->option['headers'] = $headers;
    }

    public function setJson(array $json = [])
    {
        $this->option['json'] = $json;
    }

    public function setQuery(array $query = [])
    {
        $this->option['query'] = $query;
    }

    public function setMultipart(array $multipart = [])
    {
        $this->option['multipart'] = $multipart;
    }

    /**
     * @throws GuzzleException
     * @throws \HttpException
     * @throws WorkWechatException
     */
    public function get(string $url): array
    {
        $this->response = $this->client->get($url, $this->option);
        return $this->response();
    }

    /**
     * @throws GuzzleException
     * @throws \HttpException
     * @throws WorkWechatException
     */
    public function post(string $url): array
    {
        $this->response = $this->client->post($url, $this->option);
        return $this->response();
    }

    /**
     * @throws GuzzleException
     * @throws \HttpException
     * @throws WorkWechatException
     */
    public function put(string $url): array
    {
        $this->response = $this->client->post($url, $this->option);
        return $this->response();
    }

    /**
     * @throws GuzzleException
     * @throws \HttpException
     * @throws WorkWechatException
     */
    public function delete(string $url): array
    {
        $this->response = $this->client->post($url, $this->option);
        return $this->response();
    }

    /**
     * @throws \HttpException
     * @throws WorkWechatException
     */
    protected function response(): array
    {
        if ($this->response->getStatusCode() != 200) {
            throw new \HttpException('http response fail' . $this->response->getStatusCode());
        }
        $body = json_decode((string)$this->response->getBody(), true);
        if ($body['errcode'] != 0) {
            throw new WorkWechatException($body['errmsg'], $body['errcode']);
        }
        return $body;
    }
}