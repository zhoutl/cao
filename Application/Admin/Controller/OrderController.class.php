<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-11
 * Time: 17:06
 */

namespace Admin\Controller;


class OrderController extends AdminController
{
    public function index(){
        $map=array();
        $order_no=str_check(I('order_no'));
        $consignee=str_check(I('consignee'));
        $mobile=str_check(I('mobile'));
        $start_time     =  strtotime(I('start_time'));
        $end_time     =  strtotime(I('end_time'));
        if($order_no){
            $map['order_no'] = array('like',"%{$order_no}%");
            $this->assign('order_no',$order_no);
        }
        if($consignee){
            $map['consignee'] = array('like',"%{$consignee}%");
            $this->assign('consignee',$consignee);
        }
        if($mobile){
            $map['mobile'] = array('like',"%{$mobile}%");
            $this->assign('mobile',$mobile);
        }
        if($start_time > 0 && $start_time > 0 && $start_time <= $end_time){
            $map['create_time'] = array('between',array($start_time,$end_time));
        }
        $list = $this->lists('Orders', $map,'create_time,order_id desc');
        if($list) {
            $this->assign('list',$list);
        }
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->meta_title = '订单列表';
        $this->display();
    }
    //新增订单
    public function add(){
        if(IS_POST){
            $data=I('post.');
            $result= D('Orders')->add_order($data);
            if($result['status']){
                $this->success('新增成功', Cookie('__forward__'));
            }else{
                $this->error($result['msg']);
            }
        }else{
            $this->meta_title = '新增订单';
            $this->display();
        }

    }
    //编辑订单
    public function edit(){
        if(IS_POST){
            $data=I('post.');
            $result= D('Orders')->add_order($data);
            if($result['status']){
                $this->success('新增成功', Cookie('__forward__'));
            }else{
                $this->error($result['msg']);
            }
        }else{
            $this->meta_title = '新增订单';
            $this->display();
        }

    }

    /**
     * 删除订单
     * @author zhoutonglei
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('order_id' => array('in', $id) );
        if(D('Orders')->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}