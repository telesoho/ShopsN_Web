<?php

namespace WeChat\Controller;

use Common\TraitClass\CurlTrait;
use Think\Controller;
use Common\Model\BaseModel;
use WeChat\Model\WxUserModel;

/**
 * Class WeChatController
 * @package WeChat\Controller 微信公众号基类
 */

class WeChatController extends Controller
{
    private static $app_id;           //APPID

    private static $app_secret;           //APPID

    public function __construct()
    {
        parent::__construct();

        if(!$appId_appSecret = S('appId_appSecret')){
            $appId_appSecret = BaseModel::getInstance(WxUserModel::class)->where(['id' => 1])->field(WxUserModel::$appid_d.','.WxUserModel::$appsecret_d)->find();
            S('appId_appSecret',$appId_appSecret,24*60*60);
        }
        self::$app_id = $appId_appSecret['appid'];
        self::$app_secret = $appId_appSecret['appsecret'];
    }

    /**
     * 获取accessToken
     * @return $accessToken
     */
    static function getAccessToken()
    {
        if($accessToken = S('AccessToken')){

            return $accessToken;
        }
        return self::getAccessTokenFromWx();
    }

    /**
     * @descrpition 从微信服务器获取微信ACCESS_TOKEN
     * @return $accessToken['access_token']
     */
    private static function getAccessTokenFromWx()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::$app_id.'&secret='.self::$app_secret;
        $accessToken = CurlTrait::requestWeb($url);

        if(!isset($accessToken['access_token'])){

            E('获取token失败');
        }

        S('AccessToken',$accessToken['access_token'],$accessToken['expires_in'] - 10);//保存token,时间比过期时间早10秒,避免 各种情况

        return $accessToken['access_token'];
    }












}