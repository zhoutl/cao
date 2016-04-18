<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-07
 * Time: 13:18
 */

namespace Admin\Model;


use Think\Model;

class ItemModel extends Model
{
    protected $_validate = array(
    array('material', 'checkNum', '材质不能为空', self::MUST_VALIDATE , 'function', 3),
    array('item_name', '/^[\s\S]{1,150}$/', '商品名称请不要超过50个字', 1 , 'regex', 3),
    array('stock', 'checkNum', '库存不可以小于0', 1 , 'function', 3),
    array('style_id', 'checkNum', '请选择款式', self::MUST_VALIDATE , 'function', 3),
        array('crowd', 'checkNum', '请选择商品适合人群', 1 , 'function', 3),
);

    public function addItem($data)
    {
            if(empty($data['description'])){
                $result=array('status'=>0,'msg'=>'商品详细不能为空');
            }else{
                $data['pic']=implode(',',$data['icon']);
                $data['add_time']=time();
                $info=$this->create($data);
                if($info){
                    $id=$this->add();
                    if($id){
                        $artno='110'.$id;
                        $this->where('id='.$id)->setField(array('artno'=>$artno));
                        $result=array('status'=>1,'id'=>$id);
                    }else{
                        $result=array('status'=>0,'msg'=>'新增失败');
                    }
                }else{
                    $result=array('status'=>0,'msg'=>$this->getError());
                }
            }

        return $result;

    }


    public function editItem($data)
    {
            if(empty($data['description'])){
                $result=array('status'=>0,'msg'=>'商品详细不能为空');
            }else{
                $data['pic']=implode(',',$data['icon']);
                $data['update_time']=time();
                $item=$this->find($data['id']);
                $data['stock']=$data['add_stock']+$item['stock'];
                $info=$this->create($data);
                if($info){
                    $res=$this->save();
                    if($res!== false){
                        $result=array('status'=>1,'msg'=>'更新成功');
                    }else{
                        $result=array('status'=>0,'msg'=>'更新失败');
                    }
                }else{
                    $result=array('status'=>0,'msg'=>$this->getError());
                }
            }

           return $result;

    }

}