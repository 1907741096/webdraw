<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/5/14
 * Time: 20:17
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    public $code = 400; // HTTP 状态码 404 200
    public $msg = '参数错误'; // 错误具体信息
    public $errorCode = 10000; // 自定义的错误码

    public function __construct($params = [])
    {
        if(!is_array($params)){
            return;
        //    throw new Exception('参数必须是数组');
        }
        if(array_key_exists('code',$params)){
            $this->code=$params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg=$params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode=$params['errorCode'];
        }
    }
}