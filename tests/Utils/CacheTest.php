<?php

namespace WorkWechat\Tests\Utils;

use Carbon\Carbon;
use ReflectionMethod;
use WorkWechat\Utils\FileCache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{

    public function testDelete()
    {
        $method = new ReflectionMethod(FileCache::class, 'delete');
        $method->setAccessible(true);
        $this->assertNull($method->invoke(new FileCache(1, 2, 3, 4, 5)));
    }


    public function testCacheFile()
    {
        $method = new ReflectionMethod(FileCache::class, 'cacheFile');
        $method->setAccessible(true);
        $fileName = './cache/' . md5('1,2,3,4,5') . '.json';
        $this->assertEquals($method->invoke(new FileCache(1, 2, 3, 4, 5)), $fileName);
    }
}
