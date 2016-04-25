<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-21
 * Time: 16:40
 */

namespace Wap\Controller;


use Think\Controller;

class ItemController extends  Controller
{

    public function  detail($id){
        $id=intval(I('id'));
        $item= D('Item')->field(true)->find($id);
        $style=D('Dictionary')->field(true)->find($item['style_id']);
        $item['style']=$style['title'];
        $purity=D('Dictionary')->field(true)->find($item['purity']);
        $item['purity']=$purity['title'];
        $picture=explode(',',$item['pic']);
        $pic=$picture[0];
        $this->assign('img',$pic);
        $this->assign('data',$item);
        $this->display();
    }
}