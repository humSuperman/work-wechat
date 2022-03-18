<?php

namespace WorkWechat\Utils;

use Throwable;

class WorkWechatException extends \Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}