<?php

namespace WorkWechat\Utils;

use Carbon\Carbon;

class Cache
{
    private $cacheDir = './cache/', $fileName;

    public function __construct(...$id)
    {
        $this->fileName = md5(implode(',', $id)) . '.json';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * @throws WorkWechatException
     */
    public function get(string $key)
    {
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
            throw new WorkWechatException(sprintf('cache not has key [%s]',$key));
        }
        $this->checkExpired($cacheJson);
        return $cacheJson[$key];
    }

    public function set(string $key, string $value, string $expiredAt = '')
    {
        $data = [$key => $value, 'expiredAt' => $expiredAt];
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
            $this->delete();
            throw new WorkWechatException('cache data expired');
        }
    }

    private function delete()
    {
        $file = $this->cacheFile();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    private function cacheFile(): string
    {
        return getcwd().'/'.$this->cacheDir . $this->fileName;
    }

}