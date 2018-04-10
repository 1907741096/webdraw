<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/14
 * Time: 16:02
 */

namespace app\common\model;

class User extends Base
{
    protected $name='user';
    protected $hidden=['id','status'];
}