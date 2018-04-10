<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/14
 * Time: 16:02
 */

namespace app\common\model;

class Admin extends Base
{
    protected $name='admin';
    protected $hidden=['id','status'];
}