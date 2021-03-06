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
use Home\Model\GoodsModel;
use Home\Model\GoodsCartModel;
use Common\Model\BaseModel;

/**
 * 普通商品购买
 * @author 王强
 * @version 1.0
 */
class OrdinaryCartBuy extends AbstractCart
{
    /**
     * 构造方法
     * @param array $data 购物车商品数据
     */
    public function __construct(array $data)
    {
        $this->setData($data);
    }
    
    /**
     * {@inheritDoc}
     * @see \Home\CartType\AbstractCart::getResult()
     */
    public function getResultByManyArrays()
    {
        // TODO Auto-generated method stub
        
        $cartData = $this->getData();
        
        // 获取商品信息
       
        $goodsData = BaseModel::getInstance(GoodsModel::class)->getGoodsByCartArray($cartData, GoodsCartModel::$goodsId_d);
        
        return $goodsData;
    }
    
}