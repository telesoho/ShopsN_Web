<?php
namespace WeChat\Controller;

use Common\Model\BaseModel;
use WeChat\Model\WxUserModel;

class CheckController extends WeChatController
{
    /**
     * 判断验证请求的签名信息是否正确
     * @return boolean
     */
    public function validateSignature()
    {

        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $echostr = $_GET['echostr'];

        $token = BaseModel::getInstance(WxUserModel::class)->getField(WxUserModel::$wToken_d);
        $signatureArray = array($token, $timestamp, $nonce);
        sort($signatureArray, SORT_STRING);
        if( sha1(implode($signatureArray)) == $signature ){
            echo $echostr;
        }


    }

}
