<?php

namespace Admin\Controller;

use Common\Controller\AuthController;
use Think\AjaxPage;

class DistributionController extends AuthController
{
    private $orderBy;
    private $sort;
    private $where;
    private $listRows;//每页显示条数

    /**
     * 后台分销管理
     */

    public function __construct()
    {
        parent::__construct();
        $this->orderBy = 'id';
        $this->sort    = 'desc';
        $this->setWhere();
        $this->setListRows();
    }

    public function index()
    {
        $this->display();
    }

    public function ajaxGetData()
    {
        $count    = M( 'order' )->where( $this->getWhere() )->count();
        $pageObj  = new AjaxPage( $count,$this->getListRows() );
        $data     = M( 'order' )->where( $this->getWhere() )->limit( $pageObj->firstRow,$this->getListRows() )->order( $this->orderBy . ' ' . $this->sort )->select();

        $this->assign( 'page',$pageObj->show() );
        $this->assign( 'data',$data );
        $this->display();

    }

    public function distribution()
    {
        $orderId = $_POST;
        foreach($orderId as $v ){
            if(empty(\intval($v))){
                $this->ajaxReturnData([],0,'参数错误');
            }
        }
        $status = new \Common\Controller\DistributionController($orderId);
        $status = $status -> distribution();
        if($status){
            $this->ajaxReturnData([],1);
        }
        $this->ajaxReturnData([],0,'分销失败');
    }

    /**
     * @return mixed
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @param mixed $where
     */
    public function setWhere()
    {
        if( !empty( I( 'post.timegap-1' ) ) ){
            $this->where[ 'create_time' ] = [ 'EGT',(int)\strtotime( I( 'post.timegap-1' ) ) ];
        }
        if( !empty( I( 'post.timegap-2' ) ) ){
            $this->where[ 'create_time' ] = [ 'ELT',(int)\strtotime( I( 'post.timegap-2' ) ) ];

        }
        if( I( 'post.distribution_status' ) == 0 || I( 'post.distribution_status' ) == 1 ){
            $this->where[ 'distribution_status' ] = $_POST['distribution_status'];
        }else{
            $this->where[ 'distribution_status' ] = 0;
        }
        $this->where[ 'order_status' ] = '4';
    }

    /**
     * @return mixed
     */
    public function getListRows()
    {
        return $this->listRows;
    }

    /**
     * @param mixed $listRows
     */
    public function setListRows()
    {
        if(I('post.listRows','5','/^[\/d]+$/')){
            $this->listRows = $_POST['listRows'];
            return;
        }
        $this->listRows = 10;
    }



}