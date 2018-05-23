<?php

namespace Common\Controller;
use Common\TraitClass\SmsVerification;
/**
 * [DEC] 分销类
 * Class DistributionController
 * @package Common\Controller
 */
class DistributionController
{
    use SmsVerification;
    private $order_id;//订单表id
    private $where = [];  //查询条件
    private $data = [];
    private $distribution_level;//分销等级
    private $Distribution_proportion = [];//每级分销比例 数组
    private $array = [];//储存插入数据库数据
    private $j;          //循环的次数
    private $price = [];//储存订单总金额

    public function __construct( $order_id )
    {
        $this->setOrderId( $order_id );
        $this->setJ( 1 );//循环次数/当前分销等级
        $this->key = 'Distribution';

        $this->setDistributionLevel( $this->getGroupConfig()['Distribution_lv'] );//设置分销等级
        $this->setDistributionProportion( $this->getGroupConfig() );//设置每级分销对应的比例
    }

    /**[dec]   入口
     * @return bool
     */
    public function distribution()
    {
        if( !$this->checkOrder() ){
            return false;//无数据
        }
        $status = $this->createArr();
        if( $status === false ){
            return false;
        }
        //循环结束  $this->array;所有插入数据表的数据
        //启动事务
        $model = M( 'distribution' );
        $model->startTrans();
        $status = $model->addAll( $this->array );
        if( $status > 0 ){
            //修改传过来的订单id 的distribution_status 分销状态 =1
            $order_status = M( 'order' )->where( $this->getWhere() )->save( [ 'distribution_status' => 1 ] );
        }else{
            //数据插入失败,回滚
            $model->rollback();
            return false;
        }
        if( $order_status !== false ){
            //提交事务
            $model->commit();
            return true;
        }
        //修改订单状态失败,回滚
        $model->rollback();
        return false;
    }

    /**
     * @return mixed
     */
    public function getJ()
    {
        return $this->j;
    }

    /**
     * @param mixed $j
     */
    public function setJ( $j )
    {
        $this->j = $j;
    }

    /**
     * @return array
     */
    public function getDistributionProportion()
    {
        $pro = $this->Distribution_proportion[ 'lv'.$this->getJ() ];
        if(empty($pro)){
            E('请填写完整 '.$this->getJ().' 级分销比例');
        }
        return $pro;
    }

    /**
     * @param array $Distribution_proportion
     */
    public function setDistributionProportion( $Distribution_proportion )
    {
        $this->Distribution_proportion = $Distribution_proportion;
    }

    /**
     * @return mixed
     */
    public function getDistributionLevel()
    {
        return $this->distribution_level;
    }

    /**
     * @param mixed $distribution_level
     */
    public function setDistributionLevel( $distribution_level )
    {
        $this->distribution_level = $distribution_level;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param mixed $order_id
     */
    public function setOrderId( $order_id )
    {
        if( empty( (array)$order_id ) && empty( (int)$order_id ) ){
            E( '缺少订单号' );
        }
        $this->order_id = $order_id;
    }

    /**
     * @return array
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * 设置查询条件
     */
    public function setWhere()
    {
        //时间暂不添加
        $this->where = [
            'order_status'              => [ 'EQ','4' ],//订单已收货
            'distribution_status' => [ 'EQ',0 ],//未结算分销
        ];
        //当订单id 为数组
        if( is_array( $this->getOrderId() ) ){
            $this->where[ 'id' ] = [ 'IN',join( ',',$this->getOrderId() ) ];
            return;
        }
        $this->where[ 'id' ] = [ 'EQ',$this->getOrderId() ];

    }


    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData( $data )
    {
        $this->data = $data;
    }


    private function checkOrder()
    {
        $this->setWhere();
        //查出(每个)用户符合条件的 总订单价格
        $data = M( 'order' )->where( $this->getWhere() )->field( 'user_id,sum(price_sum)' )->group( 'user_id' )->select();
        $this->setData( $data );

        if( empty( $this->getData() ) ){
            return false;
        }
        return true;
    }

    /**
     * @param array $array
     */
    private function createArr( $array = [] )
    {
        //组装数据,合并数组
        $arr = [];
        $i   = 0;
        if( $array === [] ){
            $data = $this->myArray()->getData();//获取从订单表获取的信息,拼接数据
        }else{
            $data = $array;//二级分销以后,获取上级的分销数据
        }
        foreach( $data as $k => $v ){
            //获取用户父级
            $pid = $this->getPid( $v[ 'p_id' ] );
            //获取当前分销等级 对应的分销比例
            $Proportion = $this->getDistributionProportion();
            //只有第一次循环存在此健,保存下来,后面的多级分销每次 金额都是此金额 * 比例
            if( $v[ 'sum(price_sum)' ] ){
                $this->price[ $v[ 'uid' ] ] = $v[ 'sum(price_sum)' ];
            }
            //当用户的父级=0的时候,没有上级,不分销
            if( $pid != 0 ){
                $arr[ $i ][ 'uid' ]        = $v[ 'uid' ];
                $arr[ $i ][ 'p_id' ]       = $pid;//获取当前用户的pid
                $arr[ $i ][ 'lv' ]         = $this->getJ();//当前分销等级
                $arr[ $i ][ 'price' ]      = $this->price[ $v[ 'uid' ] ] * $Proportion;//分销比例
                $arr[ $i ][ 'proportion' ] = $Proportion;//分销比例-待完善
                $arr[ $i ][ 'time' ]       = time();//分销时间
            }
            $i++;
        }
        $this->setJ( $this->getJ() + 1 ); //分销等级+1
        $this->array = \array_merge( $this->array,$arr );

        if( !empty( $arr ) && $this->getDistributionLevel() >= $this->getJ() ){
            $this->createArr( $arr );
        }
    }


    /**
     * @param $uid
     * @return mixed
     */
    private function getPid( $uid )
    {
        $pid = M( 'user' )->where( [ 'id' => $uid ] )->getField( 'p_id' );
        return $pid;
    }

    /**[dec] 改变从订单表查出的数据,增加p_id ,即当前用户的id,方便组装数据
     * @return $this
     */
    private function myArray()
    {
        $data = $this->getData();
        foreach( $data as $k => $v ){
            $data[ $k ][ 'uid' ]  = $v[ 'user_id' ];
            $data[ $k ][ 'p_id' ] = $v[ 'user_id' ];
            unset( $data[ $k ][ 'user_id' ] );
        }
        $this->setData( $data );
        return $this;
    }


}