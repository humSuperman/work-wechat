<?php

namespace WorkWechat\Tests\Utils;

use ReflectionMethod;
use WorkWechat\Utils\Cache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{

    public function testDelete()
    {
        $method = new ReflectionMethod(Cache::class, 'delete');
        $method->setAccessible(true);
        $this->assertNull($method->invoke(new Cache(1, 2, 3, 4, 5)));
    }


    public function testCacheFile()
    {
        $method = new ReflectionMethod(Cache::class, 'cacheFile');
        $method->setAccessible(true);
        $fileName = './cache/' . md5('1,2,3,4,5') . '.json';
        $this->assertEquals($method->invoke(new Cache(1, 2, 3, 4, 5)), $fileName);
    }
}
