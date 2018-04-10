<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/9
 * Time: 17:57
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code=404;
    public $msg='指定类目不存在，请检查参数';
    public $errorCode=50000;
}