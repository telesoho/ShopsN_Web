<?php
// +----------------------------------------------------------------------
// | OnlineRetailers [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2003-2023 www.yisu.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 亿速网络（http://www.yisu.cn）
// +----------------------------------------------------------------------
// | Author: 王强 <opjklu@126.com>
// +----------------------------------------------------------------------

defined( '__SERVER__' ) or define( '__SERVER__','http://' . $_SERVER[ 'HTTP_HOST' ] ); // 静态文件
// 前台
$request = substr_count( $_SERVER[ 'PATH_INFO' ],'/' ) == 1 ? substr( $_SERVER[ 'PATH_INFO' ],1 ) : strchr( substr( $_SERVER[ 'PATH_INFO' ],1 ),'/',true );

define( 'UPLOAD_PATH','Uploads/' );
$server = $_SERVER[ 'SERVER_NAME' ];

/* 数据库配置 */
return array(
    'LOAD_EXT_CONFIG'     => 'db', // 加载数据库配置文件
    'TMPL_ACTION_SUCCESS' => 'Public:dispatch_jump', // 自定义success跳转页面
    'TMPL_ACTION_ERROR'   => 'Public:dispatch_jump', // 自定义error跳转页面
    'IMG_ROOT_PATH'       => '/Uploads/goods/',
    'domin'               => 'http://' . $_SERVER[ 'SERVER_NAME' ],

    'URL_MODEL'     => 1,
    'ALIPAY_CONFIG' => [

        'notify_url'        => "http://" . $server . '/index.php/Home/Nofity/alipayNotify.html',
        'return_url'        => "http://" . $server . '/index.php/Home/Nofity/alipayReturn.html',
        'sign_type'         => strtoupper( 'RSA' ),
        'input_charset'     => strtolower( 'utf-8' ),
        'cacert'            => APP_PATH . 'PlugInUnit/PCAlipay/RSA/cacert.pem',
        'transport'         => 'http',
        'payment_type'      => "1",
        'service'           => "create_direct_pay_by_user",
        'anti_phishing_key' => "",
        'exter_invoke_ip'   => "",
    ],

    'ALIPAY_REFUND_CONFIG' => [
        'service'       => 'refund_fastpay_by_platform_pwd',
        'notify_url'    => "http://" . $server . '/adminprov.php/AlipayRefundNotice/parseAlipayNotice',
        'sign_type'     => strtoupper( 'RSA' ),
        'input_charset' => strtolower( 'utf-8' ),
        'cacert'        => APP_PATH . 'PlugInUnit/PCAlipay/RSA/cacert.pem',
        'transport'     => 'http',
        'refund_date'   => date( "Y-m-d H:i:s",time() ),
    ],

    'UnionPay'        => [
        //-------------------------------------------------------------------------------------------
        // 前台通知地址
        'frontUrl'      => "http://" . $server . '/index.php/Home/Nofity/UnionSynchronous.html',
        // 后台通知地址
        'backUrl'       => "http://" . $server . '/index.php/Home/Nofity/UnionAsynchronous.html',
        'RefundBackUrl' => "http://" . $server . '/index.php/Home/Nofity/UnionAsynchronousRefund.html',
    ],
    'AreaList'        => [
        '华东地区',
        '华东东北',
        '华南西南',
        '华中西北'
    ],

// 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE 模式); 3 (兼容模式) 默认为PATHINFO 模式
// 图片显示
//     'DATA_CACHE_TYPE'   => 'Redis',
    'TMPL_CACHE_ON'   => false,//禁止模板编译缓存
    'HTML_CACHE_ON'   => false,//禁止静态缓存
    'DB_SQL_LOG'      => True,
    'TMPL_CACHE_TIME' => 6,
    "PRODUCT_PAGE"    => 32,
    'DATA_AUTH_KEY'   => 'zhongwen',
    'balanceId'       => 4
);