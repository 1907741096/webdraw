<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/5/14
 * Time: 20:23
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Banner不存在';
    public $errorCode = 40000;
}