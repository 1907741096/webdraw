<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/13
 * Time: 11:26
 */
namespace app\lib\exception;

class TokenException extends BaseException {
    public $code=401;
    public $msg='Token已过期或无效Token';
    public $errorCode=10001;
}