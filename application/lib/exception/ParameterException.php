<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/5/15
 * Time: 12:47
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}