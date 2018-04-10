<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/15
 * Time: 16:53
 */

namespace app\common\validate;


class Sound extends Base
{
    protected $rule=[
        'id'=>'require|isPositiveInteger',
        ['style_id','require|isPositiveInteger','请选择主题|主题分类错误'],
        ['place_id','require|isPositiveInteger','请选择场景|场景分类错误'],
        ['lang_id','require|isPositiveInteger','请选择语言|语言分类错误'],
        ['name','require','请填写音频内容'],
        ['address','require','请上传音频'],
        ['status','require|number|between:-1,1','状态不合法|状态不合法|状态不合法']
    ];
    protected $scene=[
        'id'=>['id'],
        'add'=>['name','style_id','place_id','lang_id','address'],
        'edit'=>['id','name','style_id','place_id','lang_id','address'],
        'status'=>['id','status']
    ];
    public function getData(){
        $data=array();
        $params=request()->param();
        if(array_key_exists('style_id',$params)&&$params['style_id']!=''){
            $data['style_id']=$params['style_id'];
        }
        if(array_key_exists('place_id',$params)&&$params['place_id']!=''){
            $data['place_id']=$params['place_id'];
        }
        if(array_key_exists('lang_id',$params)&&$params['lang_id']!=''){
            $data['lang_id']=$params['lang_id'];
        }
        return $data;
    }
}