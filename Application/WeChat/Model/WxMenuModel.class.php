<?php

namespace WeChat\Model;

use Common\Model\BaseModel;

class WxMenuModel extends BaseModel
{
    private static $obj;
    public static $id_d;	//id

    public static $level_d;	//菜单级别

    public static $name_d;	//name

    public static $sort_d;	//排序

    public static $type_d;	//0 view 1 click

    public static $value_d;	//value

    public static $token_d;	//token

    public static $pid_d;	//上级菜单


    public static function getInitnation()
    {
        $class = __CLASS__;
        return  static::$obj= !(static::$obj instanceof $class) ? new static() : static::$obj;
    }


    public function getMenu()
    {
        $data = $this->order(self::$id_d . ' asc')->select();
        $first_menu = [];
        $sec_menu = [];
        foreach($data as $k  => $v){
            if($v[self::$pid_d ] == 0){
                $first_menu[] = $v;
            }else{
                $sec_menu[] = $v;
            }
        }
        unset($data);
        $data[0] = $first_menu; //一级菜单
        $data[1] = $sec_menu;   //二级菜单
        return $data;

    }









}