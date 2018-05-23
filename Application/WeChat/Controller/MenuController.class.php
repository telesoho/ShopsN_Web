<?php

namespace WeChat\Controller;

use Common\TraitClass\CurlTrait;
use Common\Model\BaseModel;
use WeChat\Model\WxMenuModel;

class MenuController extends WeChatController
{
    /**
     * [createMenu 向微信服务器发送创建菜单的请求]
     * @return [type] [true/flase]
     */
    public function createMenu()
    {
        $menu_json = self::getMenuFromMyWeb();
        //获取accessToken
        $access_token = self::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        $result = CurlTrait::requestWeb($url, $menu_json, 'POST');
        if($result['errcode'] == 0){
            return true;
        }
        return $result;

    }


    static function getMenuFromMyWeb()
    {
        $menuList = BaseModel::getInstance( WxmenuModel::class)->field('id,name,type,value,pid')->select();
        $data = self::setMenu($menuList);
         return $data;
    }


    //暂时只处理 view跟click 菜单类型,待完善
    static function setMenu($menuList)
    {
        //树形排布
        $menuList2 = $menuList;
        foreach($menuList as $key=>$menu){
            foreach($menuList2 as $k=>$menu2){
                if($menu['id'] == $menu2['pid']){
                    $menuList[$key]['sub_button'][] = $menu2;
                    unset($menuList[$k]);
                }
            }
        }
        //处理数据
        foreach($menuList as $key => $menu){
            //处理type和code
            if(@$menu['type'] == 'view'){
                $menuList[$key]['url'] = $menu['value'];
                //处理URL。因为URL不能在转换JSON时被转为UNICODE
                $menuList[$key]['url'] = urlencode($menuList[$key]['url']);
            }else if(@$menu['type'] == 'click'){
                $menuList[$key]['key'] = $menu['value'];
            }else if(@!empty($menu['type'])){
                $menuList[$key]['key'] = $menu['value'];
                if(!isset($menu['sub_button'])) $menuList[$key]['sub_button'] = array();
            }
            unset($menuList[$key]['value']);
            //处理PID和ID
            unset($menuList[$key]['id']);
            unset($menuList[$key]['pid']);
            //处理名字。因为汉字不能在转换JSON时被转为UNICODE
            $menuList[$key]['name'] = urlencode($menu['name']);
            //处理子类菜单
            if(isset($menu['sub_button'])){
                unset($menuList[$key]['type']);
                foreach($menu['sub_button'] as $k=>$son){
                    //处理type和code
                    if($son['type'] == 'view'){
                        $menuList[$key]['sub_button'][$k]['url'] = $son['value'];
                        $menuList[$key]['sub_button'][$k]['url'] = urlencode($menuList[$key]['sub_button'][$k]['url']);
                    }else if($son['type'] == 'click'){
                        $menuList[$key]['sub_button'][$k]['key'] = $son['value'];
                    }else{
                        $menuList[$key]['sub_button'][$k]['key'] = $son['value'];
                        $menuList[$key]['sub_button'][$k]['sub_button'] = array();
                    }
                    unset($menuList[$key]['sub_button'][$k]['value']);
                    //处理PID和ID
                    unset($menuList[$key]['sub_button'][$k]['id']);
                    unset($menuList[$key]['sub_button'][$k]['pid']);
                    //处理名字。因为汉字不能在转换JSON时被转为UNICODE
                    $menuList[$key]['sub_button'][$k]['name'] = urlencode($son['name']);
                }
            }
        }
        //整理格式
        $data = array();
        $menuList = array_values($menuList);
        $data['button'] = $menuList;
        unset($menuList);
        return urldecode(json_encode($data));

    }







}