<?php

namespace Admin\Controller;

use Admin\Model\WxTextModel;
use Admin\Model\WxUserModel;
use Admin\Model\WxMenuModel;
use Common\Model\BaseModel;
use Common\Controller\AuthController;
use Common\Tool\Tool;
use Think\Page;
use Admin\Model\WxKeywordModel;


class WeChatController extends AuthController
{
    //公众号配置
    public function index()
    {
        $data  = BaseModel::getInstance( WxUserModel::class )->where( ' id = 1 ' )->find();
        $types = C( 'we_chat_type' );

        $this->assign( 'data',$data );
        $this->assign( 'types',$types );

        unset( $data );
        unset( $types );

        $this->display();
    }

    public function save_config()
    {
        $post     = I( 'post.' );
        $validate = [ 'w_token','wxname','wxid','weixin','appid','appsecret','type' ];
        $notCheck = [
            'is_numeric' => [ 'wxid','type' ],
            'headerpic','qr','wxid'
        ];
        if ( Tool::checkPost( $post,$notCheck,true,$validate ) ) {
            $post[ 'id' ] = 1;
            $status       = BaseModel::getInstance( WxUserModel::class )->where( ' id = 1 ' )->save( $post );
            if ( $status >= 0 ) {
                $this->ajaxReturnData( [ ] );
            }
            $this->ajaxReturnData( [ ],0,'保存失败' );
        }
        $this->ajaxReturnData( [ ],0,'参数错误' );
    }


    public function Menu()
    {
        //获取最大ID
        $max_id = BaseModel::getInstance( WxMenuModel::class )->getMaxId();
        //获取所有菜单
        $data = BaseModel::getInstance( WxMenuModel::class )->getMenu();
        //一级菜单
        $p_menus = $data[ 0 ];
        //二级菜单
        $c_menus = $data[ 1 ];
        $this->assign( 'p_lists',$p_menus );
        $this->assign( 'c_lists',$c_menus );
        $this->assign( 'max_id',$max_id );
        $this->display();
    }

    //保存菜单
    public function saveMenu()
    {
        $data = I( 'post.menu' );
        $menu = BaseModel::getInstance( WxMenuModel::class )->saveMenu( $data );

        if ( $menu ) {
            $we     = new \WeChat\Controller\MenuController();
            $result = $we->createMenu();
        }

        if ( $menu && $result ) {
            $this->ajaxReturnData( [ ] );
        }

        if ( $menu && $result == false ) {
            $this->ajaxReturnData( [ ],0,'服务器菜单,创建失败' );

        }
        $this->ajaxReturnData( [ ],0,'操作失败' );

    }

    //删除菜单
    public function delMenu()
    {
        $id = I( 'post.id' );

        $status = BaseModel::getInstance( WxMenuModel::class )->delMenu( $id );
        if ( $status === 3 ) {
            $this->ajaxReturnData( [ ],0,'请先删除该菜单下的子菜单' );
        }
        if ( $status ) {
            $this->ajaxReturnData( [ ] );//删除成功
        }
        $this->ajaxReturnData( [ ],0,'删除失败' );
    }


    //文字自动回复页面

    public function text()
    {
        $num      = 2; //每页显示的数量
        $pid_strs = BaseModel::getInstance( WxKeywordModel::class )->getIdsByType( $this->type )->arrayToPidString();
        $page     = new Page( count( $pid_strs ),$num );
        $lists    = BaseModel::getInstance( WxTextModel::class )->getText( $pid_strs,$page->firstRow,$page->listRows )->splicing();
        $show     = $page->show();
        $this->assign( 'lists',$lists );
        $this->assign( 'page',$show );
        $this->display();

    }


}