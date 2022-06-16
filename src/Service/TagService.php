<?php

namespace WorkWechat\Service;


use WorkWechat\Utils\WorkWechatException;

class TagService extends BaseService
{
    const TAG_LIST = '/cgi-bin/tag/list';
    const TAG_CREATE = '/cgi-bin/tag/create';
    const TAG_UPDATE = '/cgi-bin/tag/update';
    const TAG_DELETE = '/cgi-bin/tag/delete';
    const TAG_USER_LIST = '/cgi-bin/tag/get';
    const BIND_TAG_USERS = '/cgi-bin/tag/addtagusers';
    const DELETE_TAG_USERS = '/cgi-bin/tag/deltagusers';

    /**
     *
     * @throws WorkWechatException
     */
    public function list(): array
    {
        $query = [
            'access_token' => $this->getAccessToken(),
        ];
        $this->client->setQuery($query);
        return $this->client->get(self::TAG_LIST);
    }

    /**
     * @throws WorkWechatException
     */
    public function create(string $tagName): array
    {
        $query = [
            'access_token' => $this->getAccessToken(),
        ];
        $json = [
            'tagname' => $tagName,
        ];
        $this->client->setQuery($query);
        $this->client->setJson($json);
        return $this->client->post(self::TAG_CREATE);
    }

    /**
     * @throws WorkWechatException
     */
    public function update(int $tagId, string $tagName): array
    {
        $query = [
            'access_token' => $this->getAccessToken(),
        ];
        $json = [
            'tagid' => $tagId,
            'tagname' => $tagName,
        ];
        $this->client->setQuery($query);
        $this->client->setJson($json);
        return $this->client->post(self::TAG_UPDATE);
    }

    /**
     * @throws WorkWechatException
     */
    public function delete(int $tagId): array
    {
        $query = [
            'access_token' => $this->getAccessToken(),
            'tagid' => $tagId,
        ];
        $this->client->setQuery($query);
        return $this->client->get(self::TAG_DELETE);
    }

    /**
     * @throws WorkWechatException
     */
    public function userList(int $tagId): array
    {
        $query = [
            'access_token' => $this->getAccessToken(),
            'tagid' => $tagId,
        ];
        $this->client->setQuery($query);
        return $this->client->get(self::TAG_USER_LIST);
    }

    /**
     * @throws WorkWechatException
     */
    public function bindTagUser(int $tagId, string ...$userId): array
    {
        $this->validateTotal($userId);
        $query = [
            'access_token' => $this->getAccessToken(),
        ];
        $data = [
            'tagid' => $tagId,
            'userlist' => $userId,
        ];
        $this->client->setQuery($query);
        $this->client->setJson($data);
        return $this->client->post(self::BIND_TAG_USERS);

    }

    /**
     * @throws WorkWechatException
     */
    public function bindTagDepartment(int $tagId, int ...$departmentId): array
    {
        $this->validateTotal($departmentId);
        $query = [
            'access_token' => $this->getAccessToken(),
        ];
        $data = [
            'tagid' => $tagId,
            'partylist' => $departmentId,
        ];
        $this->client->setQuery($query);
        $this->client->setJson($data);
        return $this->client->post(self::BIND_TAG_USERS);
    }

    /**
     * @throws WorkWechatException
     */
    public function deleteTagUser(int $tagId, string ...$userId): array
    {
        $this->validateTotal($userId);
        $query = [
            'access_token' => $this->getAccessToken(),
        ];
        $data = [
            'tagid' => $tagId,
            'userlist' => $userId,
        ];
        $this->client->setQuery($query);
        $this->client->setJson($data);
        return $this->client->post(self::DELETE_TAG_USERS);
    }

    /**
     * @throws WorkWechatException
     */
    public function deleteTagDepartment(int $tagId, int ...$departmentId): array
    {
        $this->validateTotal($departmentId);
        $query = [
            'access_token' => $this->getAccessToken(),
        ];
        $data = [
            'tagid' => $tagId,
            'partylist' => $departmentId,
        ];
        $this->client->setQuery($query);
        $this->client->setJson($data);
        return $this->client->post(self::DELETE_TAG_USERS);
    }

    /**
     * @throws WorkWechatException
     */
    private function validateTotal(array $data)
    {
        if (count($data) > 100) {
            throw new WorkWechatException('单次请求id数量不可超过100');
        }
    }
}