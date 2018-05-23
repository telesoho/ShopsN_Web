<?php

namespace Admin\Model;

use Common\Model\BaseModel;

class WxKeywordModel extends BaseModel
{
    private static $obj;

    private $arr = [];


	public static $id_d;	//表id

	public static $keyword_d;	//关键词

	public static $pid_d;	//对应表ID

	public static $token_d;	//token

	public static $type_d;	//关键词操作类型


    public static function getInitnation()
    {
        $class = __CLASS__;
        return  static::$obj= !(static::$obj instanceof $class) ? new static() : static::$obj;
    }


    /**
     * @return $this
     */
    public function getIdsByType($type)
    {
        $where = ['type'  => $type];

        $this->arr = $this->field(self::$id_d,self::$pid_d)->where($where)->select();
        showData( $this->arr ,1);

        return $this;
    }

    /**
     * @return string
     */
    public function arrayToString()
    {
        showData( $this->arr ,1);
        $arr = [];
        foreach($this->arr as $k  => $v)
        {
            $arr[$k] = $v['pid'];
        }
        return join(',' ,$arr);
    }






}