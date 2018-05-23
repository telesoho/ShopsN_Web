<?php
// +----------------------------------------------------------------------
// | OnlineRetailers [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2003-2023 www.yisu.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 亿速网络（http://www.yisu.cn）
// +----------------------------------------------------------------------
// | Author: 王强 <13052079525>
// +----------------------------------------------------------------------
// |简单与丰富！让外表简单一点，内涵就会更丰富一点。
// +----------------------------------------------------------------------
// |让需求简单一点，心灵就会更丰富一点。
// +----------------------------------------------------------------------
// |让言语简单一点，沟通就会更丰富一点。
// +----------------------------------------------------------------------
// |让私心简单一点，友情就会更丰富一点。
// +----------------------------------------------------------------------
// |让情绪简单一点，人生就会更丰富一点。
// +----------------------------------------------------------------------
// |让环境简单一点，空间就会更丰富一点。
// +----------------------------------------------------------------------
// |让爱情简单一点，幸福就会更丰富一点。
// +----------------------------------------------------------------------
namespace Home\CartType\Type;

use Home\CartType\AbstractCart;
use Common\Model\BaseModel;
use Home\Model\GoodsPackageSubModel;
use Home\Model\GoodsCartModel;
use Home\Model\GoodsModel;

/**
 * 套餐购买
 * 
 * @author 王强
 * @version 1.0
 */
class PackageCartBuy extends AbstractCart
{

    /**
     * 构造方法
     * 
     * @param array $data
     *            购物车商品数据
     */
    public function __construct(array $data)
    {
        $this->setData($data);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Home\CartType\AbstractCart::getResult()
     */
    public function getResultByManyArrays()
    {
        // TODO Auto-generated method stub
        $cartData = $this->getData();
        
        if (empty($cartData)) {
            return [];
        }
        
        // 获取套餐数据
        
        $packageData = BaseModel::getInstance(GoodsPackageSubModel::class)->getPackageByCart($cartData, GoodsCartModel::$goodsId_d);
        
        
        
        if (empty($packageData)) {
            return [];
        }
        
        $cartData = $this->parseData($packageData);
        
        //处理商品
        
        $cartData = BaseModel::getInstance(GoodsModel::class)->getGoodsByPackage($cartData, GoodsCartModel::$goodsId_d);
     
        
        return $cartData;
    }

    /**
     * [0] => Array
        (
            [id] => 1
            [user_id] => 3
            [goods_id] => 3624
            [goods_num] => 4
            [attribute_id] => 0
            [price_new] => 1
            [integral_rebate] => 
            [is_del] => 0
            [buy_type] => 1
            [ware_id] => 0
        )

    [1] => Array
        (
            [id] => 2
            [user_id] => 3
            [goods_id] => 3630
            [goods_num] => 4
            [attribute_id] => 0
            [price_new] => 1
            [integral_rebate] => 
            [is_del] => 0
            [buy_type] => 1
            [ware_id] => 0
        )
     * Array
     * (
     * [3623] => 0.50
     * [3624] => 0.50
     * )
     * 组合购物车与优惠套餐数据
     * @param array $data 优惠套餐
     * @return array          
     */
    private function parseData(array $data)
    {
        $cartData = $this->getData();
        
        $cartData = BaseModel::getInstance(GoodsPackageSubModel::class)->covertKeyById($cartData, GoodsCartModel::$goodsId_d);
        
        $comoArray = [];        
        
        //扩充数组
        foreach ($data as $key =>  $value) {
            
            if (!array_key_exists($value[GoodsPackageSubModel::$packageId_d], $cartData)) {
                continue;
            }
            
            $cartData[$value[GoodsPackageSubModel::$packageId_d]][GoodsCartModel::$priceNew_d] = $value[GoodsPackageSubModel::$discount_d];
            
            $cartData[$value[GoodsPackageSubModel::$packageId_d]][GoodsCartModel::$goodsId_d] = $value[GoodsPackageSubModel::$goodsId_d];
            array_push($comoArray, $cartData[$value[GoodsPackageSubModel::$packageId_d]]);
        }
        unset($cartData, $data);
        return $comoArray;
    }
    
}