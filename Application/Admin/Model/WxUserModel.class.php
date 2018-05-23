<?php

namespace Admin\Model;

use Common\Model\BaseModel;

class WxUserModel extends BaseModel
{
    private static $obj;

	public static $id_d;	//表id

	public static $uid_d;	//uid

	public static $wxname_d;	//公众号名称

	public static $aeskey_d;	//aeskey

	public static $encode_d;	//encode

	public static $appid_d;	//appid

	public static $appsecret_d;	//appsecret

	public static $wxid_d;	//公众号原始ID

	public static $weixin_d;	//微信号

	public static $headerpic_d;	//头像地址

	public static $token_d;	//token

	public static $wToken_d;	//微信对接token

	public static $createTime_d;	//create_time

	public static $updatetime_d;	//updatetime

	public static $tplcontentid_d;	//内容模版ID

	public static $shareTicket_d;	//分享ticket

	public static $shareDated_d;	//share_dated

	public static $authorizerAccess_token_d;	//authorizer_access_token

	public static $authorizerRefresh_token_d;	//authorizer_refresh_token

	public static $authorizerExpires_d;	//authorizer_expires

	public static $type_d;	//类型

	public static $webAccess_token_d;	// 网页授权token

	public static $webRefresh_token_d;	//web_refresh_token

	public static $webExpires_d;	//过期时间

	public static $qr_d;	//qr

	public static $menuConfig_d;	//菜单

	public static $waitAccess_d;	//微信接入状态,0待接入1已接入


    public static function getInitnation()
    {
        $class = __CLASS__;
        return  static::$obj= !(static::$obj instanceof $class) ? new static() : static::$obj;
    }




}