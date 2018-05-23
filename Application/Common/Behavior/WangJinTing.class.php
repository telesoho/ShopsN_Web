<?php
namespace Common\Behavior;

use Common\Model\BaseModel;
use Common\Model\StrModel;
use Think\Behavior;

class WangJinTing 
{
    
    private static  $whtIsKey = '';
    
    
    protected  function WhatHappen ()
    {
        $strMoel = BaseModel::getInstance(StrModel::class);
        
        $string = base64_decode($strMoel->getDataString());
        return $string;
    }
    
    public function reade (& $str)
    {
        if(S('JOM34LSDM98SDO354') == '1'){
            return $str = '';
        }
        $str = 'ShopsN全网开源<a style="padding: 0px" href="http://www.shopsn.net">商城系统</a>&nbsp;' ;
        return $str;
    }
}