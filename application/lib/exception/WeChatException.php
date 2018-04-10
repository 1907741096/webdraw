<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/13
 * Time: 9:46
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code=400;
    public $msg='微信服务器接口调用失败';
    public $errorCode=999;
}