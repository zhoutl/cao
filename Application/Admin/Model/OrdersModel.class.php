<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-12
 * Time: 15:55
 */

namespace Admin\Model;


use Think\Model;

class OrdersModel extends Model
{

    public function add_order($data){
        $item_ids=$data['item'];
        $info=array();
        $info['order_no']=date('YmdHis') . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        $info['consignee']=str_check($data['consignee']);
        $info['mobile']=str_check($data['mobile']);
        $info['address']=str_check($data['address']);
        $info['shipping_sn']=str_check($data['shipping_sn']);
        $info['desc']=str_check($data['desc']);
        $info['amount']=round(intval($data['amount']),2);
        $info['create_time']=time();
        while(true){
            if(empty($item_ids)){
                $result=array('status'=>0,'msg'=>'请选择商品');
                break;
            }
            for($i=0;$i<count($item_ids);$i++){
                $item_detail=D('Item')->field(true)->find(intval($item_ids[$i]));
                $str='item_'.$item_ids[$i];
                $order_item['quantity']=intval($data[$str]);
                if($order_item['quantity']<=0){
                    $status=1;
                }
                $sell_number= D('OrderItem')->get_sell($item_ids[$i]);
                $now_stock=$item_detail['stock']-$sell_number;
                if($now_stock < $order_item['quantity']){
                    $status=2;
                }
            }
            if($status==2){
                $result=array('status'=>0,'msg'=>'库存不足');
                break;
            }
            if($status==1){
                $result=array('status'=>0,'msg'=>'商品数量不可以为0');
                break;
            }
            if($info['amount']<=0){
                $result=array('status'=>0,'msg'=>'总金额应大于0');
                break;
            }
            if(empty($info['consignee'])){
                $result=array('status'=>0,'msg'=>'请填写收货人');
                break;
            }
            if(!($this->create($info))){
                $result=array('status'=>0,'msg'=>$this->error());
                break;
            }
            $id=$this->add();
            $res= D('OrderItem')->add_order_item($data,$id);
            if($res['status']){
                $result=array('status'=>1,'msg'=>'新增成功');
            }else{
                $result=array('status'=>0,'msg'=>$res['msg']);
            }

            break;
        }
        return $result;
    }
    public function edit_order(){

    }




}