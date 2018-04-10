<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/15
 * Time: 11:20
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code=404;
    public $msg='用户不存在';
    public $errorCode=60000;
}