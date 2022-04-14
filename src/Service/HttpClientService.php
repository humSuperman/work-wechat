<?php

namespace WorkWechat\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use WorkWechat\Utils\WorkWechatException;

class HttpClientService
{
    private $client, $response;

    private $option;

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
     * @throws WorkWechatException
     */
    public function get(string $url): array
    {
        try {
            $this->response = $this->client->get($url, $this->option);
        } catch (GuzzleException $e) {
            throw new WorkWechatException($e->getMessage(), $e->getCode());
        }
        return $this->response();
    }

    /**
     * @throws WorkWechatException
     */
    public function post(string $url): array
    {
        try {
            $this->response = $this->client->post($url, $this->option);
        } catch (GuzzleException $e) {
            throw new WorkWechatException($e->getMessage(), $e->getCode());
        }
        return $this->response();
    }

    /**
     * @throws WorkWechatException
     */
    public function put(string $url): array
    {
        try {
            $this->response = $this->client->put($url, $this->option);
        } catch (GuzzleException $e) {
            throw new WorkWechatException($e->getMessage(), $e->getCode());
        }
        return $this->response();
    }

    /**
     * @throws WorkWechatException
     */
    public function delete(string $url): array
    {
        try {
            $this->response = $this->client->delete($url, $this->option);
        } catch (GuzzleException $e) {
            throw new WorkWechatException($e->getMessage(), $e->getCode());
        }
        return $this->response();
    }

    /**
     * @throws WorkWechatException
     */
    protected function response(): array
    {
        if ($this->response->getStatusCode() != 200) {
            throw new WorkWechatException('http response fail' . $this->response->getStatusCode());
        }
        $body = json_decode((string)$this->response->getBody(), true);
        if ($body['errcode'] != 0) {
            throw new WorkWechatException($body['errmsg'], $body['errcode']);
        }
        return $body;
    }
}