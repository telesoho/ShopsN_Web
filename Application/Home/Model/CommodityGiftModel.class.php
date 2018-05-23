<?php
// +----------------------------------------------------------------------
// | OnlineRetailers [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2003-2023 www.yisu.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 亿速网络（http://www.yisu.cn）
// +----------------------------------------------------------------------
// | Author: 王强 <opjklu@126.com>\n
// +----------------------------------------------------------------------
namespace Home\Model;

use Common\Model\BaseModel;
use Think\Controller;
use Common\TraitClass\NoticeTrait;

/**
 * 赠品模型
 * @author  王强<opjklu@126.com>
 * @version 1.0.0
 */
class CommodityGiftModel extends BaseModel
{
    use NoticeTrait;

    private static $obj;


    public static $id_d;    //自增主键

    public static $goodsId_d;    //type：0，goods_id为赠品id对应商品id，type：1，goods——id为商品id（父类id）

    public static $type_d;    //促销类型 0 为满赠 1 为商品赠品

    public static $expression_d;    //满赠类型价格 为商品赠品时为0

    public static $startTime_d;    //开始时间

    public static $endTime_d;    //结束时间

    public static $createTime_d;    //创建时间

    public static $saveTime_d;    //更新时间

    public static $description_d;    //描述

    public static $status_d;    //判读状态 1为显示 0为删除

    public static $group_d;    //适用人群


    public static function getInitnation()
    {
        $class = __CLASS__;
        return static::$obj = !(static::$obj instanceof $class) ? new static() : static::$obj;
    }


    /**
     * @param $type  0:满赠  1:单品赠送
     * @param $info  $type->0时,订单价格,$type->1时,商品id
     * @return mixed 赠品信息
     */

    public function getGiftList($type, $info)
    {
        $time = time();
        $Level_id = $this->getUserLevel_id();

        $where = [
            'start_time' => ['ELT', $time],
            'end_time'   => ['EGT', $time],
            'status'     => 1,
        ];

        /*------------------------下面为 满赠 赠品信息 -----------------------*/

        if ($type == '0') {
            $where = [
                'type'       => 0,
                'expression' => ['ELT', $info['totleMoney']],
            ];
            //满减,按照满减金额最高的来选取
            $goods_ids = M('commodity_gift')->where($where)->where("find_in_set($Level_id,`group`)")->order('expression desc')->getField('goods_id');


            //判断满赠商品是否只有一个
            if(! $this->checkGoodsIds($goods_ids)){
                $gift_goods_info = M('goods')->alias('g')->join('db_goods_images as p on g.id = p.goods_id','LEFT')->field('g.id,g.title,p.pic_url')->where(['g.id' => $goods_ids, 'p.status' => 1, 'p.is_thumb' => 1])->select();
                session('user_gift_0',$gift_goods_info);
                return $gift_goods_info;
            }

            //查询满足相应的赠品信息
            $gift_goods_info = M('goods')->alias('g')->join('db_goods_images as p on g.id = p.goods_id','LEFT')->field('g.id,g.title,p.pic_url')->where(['g.id' => ['IN',$goods_ids], 'p.status' => 1, 'p.is_thumb' => 1])->select();
            session('user_gift_0',$gift_goods_info);
            return $gift_goods_info;
        }
        /*------------------------下面为单品 赠品信息 -----------------------*/
        //购物车多商品时

        $arr = [];
        $info = join(',', $info);

        //判断是否只有一个商品id
        if($this->checkGoodsIds($info)){

            //id => db_commodity_gift表的id
            $ids = M('commodity_gift')->field('id,goods_id')->where($where)->where(['goods_id' => ['IN', $info],'type' => 1])->select();


            foreach ($ids as $k => $v) {

                //查询 gift表的 goods_id
                $gift_goods_id = M('gifts')->where(['gift_id' => $v['id'], 'parent_id' => $v['goods_id']])->getField('goods_id');


                //用goods_id查询 赠品的信息以及图片
                $gift_goods_info = M('goods')->alias('g')->join('db_goods_images as p on g.id = p.goods_id','LEFT')->field('g.id,g.title,p.pic_url')->where(['g.id' => $gift_goods_id, 'p.status' => 1, 'p.is_thumb' => 1])->find();

                //拼接数组 key为 赠送商品的id value 为 赠品的信息
                $arr[$v['goods_id']] = $gift_goods_info;

            }
            session('user_gift_1',$arr);

            return $arr;
        }




        //只有一个商品 $info为  父类id

        //id => db_commodity_gift表的id

        $id = M('commodity_gift')->where($where)->where(['goods_id' => $info ,'type' => 1])->getField('id');

        //查询 gift表的 goods_id
        $gift_goods_id = M('gifts')->where(['gift_id' => $id, 'parent_id' => $info])->getField('goods_id');

        $gift_goods_info = M('goods')->alias('g')->join('db_goods_images as p on g.id = p.goods_id','LEFT')->field('g.id,g.title,p.pic_url')->where(['g.id' => $gift_goods_id, 'p.status' => 1, 'p.is_thumb' => 1])->find();

        $arr[$gift_goods_id] = $gift_goods_info;

        session('user_gift_1',$arr);

        return $arr;


    }


    /**
     * @return 返回用户等级id
     */
    public function getUserLevel_id()
    {
        return M('user')->where(['user_id' => $_SESSION['user_id']])->getField('level_id');
    }


    /**
     * @param null $goodsid
     * @return bool
     */
    public function checkGoodsIds($goodsid = null)
    {
        if($goodsid == null){
            return false;
        }

        if(preg_match('/^[0-9]+,[0-9]+/',$goodsid)){
            return true;
        }
        return false;
    }


}