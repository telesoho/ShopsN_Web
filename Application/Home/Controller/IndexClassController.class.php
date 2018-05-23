<?php
namespace Home\Controller;

use Think\Controller;

class IndexClassController extends Controller
{
    /*
     *  首页获取分类 接口
     */
    public function getGoodsClass()
    {
        $page = max( 1,I( 'post.page/d' ) );
        $goods = S('IndexGoodsPage-'.$page);
        if($goods){
            $this->ajaxReturn(['data'=>$goods,'status'=>1]);
        }
        $goods = D('GoodsClass')->getGoodsClassPage($page);//无广告
        if($goods['goods'][0]['id']){
            $goods['Ad'] =  $this->getAdList($page);
            S('IndexGoodsPage-'.$page,$goods,3600);
            $this->ajaxReturn(['data'=>$goods,'status'=>1]);
        }
        $this->ajaxReturn(['data'=>[],'status'=>0]);
    }

    private function getAdList($page)
    {
        $where['enabled'] = 1;
        $where['ad_space_id'] = ['IN','37,38'];
        $where['platform'] = 1;
        $where['title'] = $page.'楼';
        $nowtime = time();
        $where['start_time'] = [
            'elt',
            $nowtime
        ];
        $where['end_time'] = [
            'egt',
            $nowtime
        ];
        $rs = M('ad')->field('ad_link,pic_url')->where($where)->order('ad_space_id')->select();

        $data['m']['ad_link'] = $rs[1]['ad_link'] ? $rs[1]['ad_link'] : 0;
        $data['m']['pic_url'] = $rs[1]['pic_url'] ? $rs[1]['pic_url'] : '#';
        $data['b']['ad_link'] = $rs[0]['ad_link'] ? $rs[0]['ad_link'] : 0;
        $data['b']['pic_url'] = $rs[0]['pic_url'] ? $rs[0]['pic_url'] : '#';
        return $data;
    }
}