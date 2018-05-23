<?php

return array(

    //微信菜单类型与描述 一级对应菜单 的健
    'wx_menu_type' => [
        0 => ['view','跳转URL','url'],
        1 => ['click','点击推事件','key'],
        2 => ['scancode_push','扫码推事件'],
        3 => ['scancode_waitmsg','扫码推事件且弹出“消息接收中”提示框'],
        4 => ['pic_sysphoto','弹出系统拍照发图'],
        5 => ['pic_photo_or_album','弹出拍照或者相册发图'],
        6 => ['pic_weixin','弹出微信相册发图器'],
        7 => ['location_select','弹出地理位置选择器'],
    ],


);