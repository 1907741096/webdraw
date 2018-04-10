<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/5/14
 * Time: 20:14
 */

namespace app\lib\exception;


use  Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    public function render(\Exception $e)
    {
         if ($e instanceof BaseException){
             //如果是自定义的异常
            $this->code=$e->code;
            $this->msg=$e->msg;
            $this->errorCode=$e->errorCode;
         }else{
             //Config::get('app_debug');
             if(config('app_debug')){
                 // return default error page;
                 return parent::render($e);
             }else{
                 $this->code = 500;
                 $this->msg = '服务器内部错误';
                 $this->errorCode = 999;
                 $this->recordErrorLog($e);
             }

         }
         $resquest = Request::instance();
         $result=[
             'msg' => $this->msg,
             'error_code' => $this->errorCode,
             'request_url' => $resquest->url()
         ];
         return json($result,$this->code);
    }
    private function recordErrorLog(\Exception $e){
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}