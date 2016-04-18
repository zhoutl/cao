<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-13
 * Time: 13:35
 */

namespace Admin\Model;


use Think\Model;

class OrderItemModel extends Model
{

    protected $_validate=array();
    protected $_auto=array(
        array('add_time', 'time', self::MODEL_INSERT, 'function'),
    );
    public function add_order_item($data,$id){
        $item_ids=$data['item'];
        for($i=0;$i<count($item_ids);$i++){
            $order_item=array();
            $item_detail=D('Item')->field(true)->find(intval($item_ids[$i]));
            $order_item['order_id']=$id;
            $order_item['item_id']=intval($item_ids[$i]);
            $str='item_'.$item_ids[$i];
            $order_item['quantity']=intval($data[$str]);
            $order_item['item_name']=$item_detail['item_name'];
            $order_item['add_time']=time();
                //订单商品表添加数据
            if($this->create($order_item)){
                    $this->add();
                    $map=array('id'=>intval($item_ids[$i]));
                    D('Item')->where($map)->setInc('sell_num',intval($order_item['quantity']));
                    $result=array('status'=>1,'msg'=>'新增成功');
            }else{
                    $result=array('status'=>0,'msg'=>$this->error());
                    break;
            }

        }
        return $result;
    }

    //获得商品售卖数量
    public function get_sell($item_id)
    {
        $number=$this->where('item_id='.$item_id)->sum('quantity');
        return $number;
    }
}