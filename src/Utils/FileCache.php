<?php

namespace WorkWechat\Utils;

use Carbon\Carbon;
use Psr\SimpleCache\CacheInterface;

class FileCache implements CacheInterface
{
    private $cacheDir = './cache/', $fileName;
    private $key;

    public function __construct()
    {
        if (!is_dir($this->cacheDir())) {
            mkdir($this->cacheDir(), 0755, true);
        }
    }

    public function setFileName($fileName){
        $this->fileName = $fileName;
    }

    /**
     * @throws WorkWechatException
     */
    public function get($key, $default = null)
    {
        $this->key = $key;
        $file = $this->cacheFile();
        if (!file_exists($file) || !is_readable($file)) {
            throw new WorkWechatException('cache file not exist');
        }
        $data = file_get_contents($file);
        if (empty($data)) throw new WorkWechatException('cache data is empty');
        $cacheJson = json_decode($data, true);
        if (!is_array($cacheJson)) {
            throw new WorkWechatException('cache data not json');
        }
        if (!array_key_exists($key, $cacheJson)) {
            throw new WorkWechatException(sprintf('cache not has key [%s]', $key));
        }
        $this->checkExpired($cacheJson);
        return $cacheJson[$key] ?? $default;
    }

    public function set($key, $value, $ttl = null)
    {
        $ttl = empty($ttl) ? 7000 : $ttl;
        $this->key = $key;
        $data = [$key => $value, 'expiredAt' => Carbon::now()->addSeconds($ttl)->format('Y-m-d H:i:s')];
        file_put_contents($this->cacheFile(), json_encode($data));
    }

    /**
     * @throws WorkWechatException
     */
    private function checkExpired(array $cache)
    {
        if (empty($cache['expiredAt'])) {
            return;
        }
        if (Carbon::now()->gt($cache['expiredAt'])) {
            $this->delete($this->cacheFile());
            throw new WorkWechatException('cache data expired');
        }
    }

    /**
     * @param $key
     */
    public function delete($key)
    {
        if (file_exists($key)) {
            unlink($key);
        }
    }

    private function cacheFile(): string
    {
        return $this->cacheDir() . $this->key . '_' . $this->fileName;
    }

    private function cacheDir(): string
    {
        return php_sapi_name() == 'fpm-fcgi' ? './.' . $this->cacheDir : $this->cacheDir;
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function getMultiple($keys, $default = null)
    {
        // TODO: Implement getMultiple() method.
    }

    public function setMultiple($values, $ttl = null)
    {
        // TODO: Implement setMultiple() method.
    }

    public function deleteMultiple($keys)
    {
        // TODO: Implement deleteMultiple() method.
    }

    public function has($key)
    {
        // TODO: Implement has() method.
    }
}