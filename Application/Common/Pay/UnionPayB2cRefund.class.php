<?php

namespace Common\Pay;

use PlugInUnit\UnionPayB2c\sdk\AcpService;
use PlugInUnit\UnionPayB2c\sdk\SDKConfig;

class UnionPayB2cRefund
{
    private $params = [];

    public function setPayData( $data )
    {
        $this->params = array(

            //以下信息非特殊情况不需要改动
            'version'     => '5.0.0',              //版本号
            'encoding'    => 'utf-8',              //编码方式
            'signMethod'  => '01',              //签名方法
            'txnType'     => '04',                  //交易类型
            'txnSubType'  => '00',              //交易子类
            'bizType'     => '000201',              //业务类型
            'accessType'  => '0',              //接入类型
            'channelType' => '07',              //渠道类型
            'backUrl'     => C( 'UnionPay' )[ 'RefundBackUrl' ], //后台通知地址

            'merId' => $data[ 'mchid' ],            //商户代码，请改成自己的测试商户号，此处默认取demo演示页面传递的参数
// 		'reqReserved' =>'透传信息',            //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
        );
    }

    public function setReturnMonery( $money )
    {
        $this->params[ 'txnAmt' ] = $money;
    }

    public function refundMonery( $obj,$info )
    {
        $orderId                     = \str_replace( '-','',$info[ 'order_sn_id' ] );
        $origQryId                   = M( 'unionrefund' )->where( [ 'order_sn_id_r' => $orderId ] )->getField( 'origQryId' );
        $this->params[ 'origQryId' ] = $origQryId;
        $this->params[ 'orderId' ]   = $orderId.\substr(time(),6);
        $this->params[ 'txnTime' ]   = date( 'Ymdhms' );

        AcpService::sign( $this->params );

        $result_arr = AcpService::post( $this->params,SDKConfig::SDK_BACK_TRANS_URL );
        \file_put_contents('./Uploads/qqq/result_arr.txt',\print_r($result_arr,true));
        if( !AcpService::validate( $result_arr ) ){
            return false;
        }
        if( $result_arr[ "respCode" ] == "00" ){
            return true;
        }

    }
}