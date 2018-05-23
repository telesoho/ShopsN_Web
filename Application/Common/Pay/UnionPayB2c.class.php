<?php

namespace Common\Pay;

use PlugInUnit\UnionPayB2c\sdk\AcpService;
use PlugInUnit\UnionPayB2c\sdk\SDKConfig;

class UnionPayB2c
{
    private $params = [];

    /**初始化订单数据
     * UnionPayB2c constructor.
     * @param $data
     */
    public function setPayData( $data )
    {

        if( !isset( $data ) || !\is_array( $data ) ){
            E( '参数错误' );
        }
        $this->params = array(
            //以下信息非特殊情况不需要改动
            'version'      => '5.0.0',                 //版本号
            'encoding'     => 'utf-8',                  //编码方式
            'txnType'      => '01',                      //交易类型
            'txnSubType'   => '01',                  //交易子类
            'bizType'      => '000201',                  //业务类型
            'frontUrl'     => C( 'UnionPay' )[ 'frontUrl' ],  //前台通知地址
            'backUrl'      => C( 'UnionPay' )[ 'backUrl' ],      //后台通知地址
            'signMethod'   => '01',                  //签名方法
            'channelType'  => '08',                  //渠道类型，07-PC，08-手机
            'accessType'   => '0',                  //接入类型
            'currencyCode' => '156',              //交易币种，境内商户固定156
            'merId'        => $data[ 'mchid' ],        //商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
        );

    }

    public function pay( $obj )
    {
        //前台请求地址
        $info = $obj->getInfo();
        $this->setOrderInfo( $info );
        AcpService::sign( $this->params );
        $html_form = AcpService::createAutoFormHtml( $this->params,SDKConfig::SDK_FRONT_TRANS_URL );
        die( $html_form );
    }

    private function setOrderInfo( $info )
    {
        $this->params[ 'orderId' ] = \str_replace( '-','',$info[ 'order_sn_id' ] );
        $this->params[ 'txnAmt' ]  = $info[ 'price_sum' ] * 100;
        $this->params[ 'txnTime' ] = date( 'Ymdhms' );

    }


}