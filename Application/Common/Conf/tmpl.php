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

return [

    'TMPL_PARSE_STRING' => [
        '__CSS__' => __SERVER__ . '/Public/' . __STATIC_MOUDLE__ . '/css',
        '__JS__' => __SERVER__ . '/Public/' . __STATIC_MOUDLE__ . '/js',
        '__IMG__' => __SERVER__ . '/Public/' . __STATIC_MOUDLE__ . '/img',
        '__PHP__'=>__SERVER__.'/Public/'.__STATIC_MOUDLE__.'/php',
        '__LAYER__' => __SERVER__ . '/Public/Common/js/layer',
        '__COMMON__' => __SERVER__ . '/Public/Common',
        '__SERVER__' => __SERVER__,
        '__CDN_JQ__' => '//lib.sinaapp.com/js/jquery/1.7.2/jquery.min.js',
//        '__CDN_JQ__' => 'http://lib.sinaapp.com/js/jquery/1.7.2/jquery.min.js',
        '__CDN_LAZYLOAD_JS__' => '//cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.min.js'
//        '__CDN_LAZYLOAD_JS__' => 'https://cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.min.js'
    ]
];