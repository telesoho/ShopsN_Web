<?php

namespace Admin\Model;

use Common\Model\BaseModel;

class WxTextModel extends BaseModel
{
    private $type = 'TEXT';  //自动回复的类型
    private static $obj;
    private $info;


    public static $id_d;    //表id

    public static $uid_d;    //用户id

    public static $uname_d;    //用户名

    public static $keyword_d;    //关键词

    public static $precisions_d;    //precisions

    public static $text_d;    //text

    public static $createtime_d;    //创建时间

    public static $updatetime_d;    //更新时间

    public static $click_d;    //点击

    public static $token_d;    //token


    public static function getInitnation()
    {
        $class = __CLASS__;
        return static::$obj = !(static::$obj instanceof $class) ? new static() : static::$obj;
    }

    public function getText($pids,$firstRow,$listRows)
    {
        $this->info = $this->field(self::$keyword_d . ',' . self::$text_d)
                ->where(['id' => ['IN', $pids]])
                ->limit($firstRow . ',' . $listRows)
                ->order(self::$createtime_d . ' desc')
                ->select();
    }

    //把keyword 表的id拼接过来,避免连表查
    public function splicing()
    {

    }



}