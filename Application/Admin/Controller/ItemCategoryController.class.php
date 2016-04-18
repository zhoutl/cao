<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-06
 * Time: 11:06
 */

namespace Admin\Controller;


class ItemCategoryController extends AdminController
{

    public function index(){
        $pid  = I('get.pid',0);
        if($pid){
            $data = M('Item_category')->where("id={$pid}")->field(true)->find();
            $this->assign('data',$data);
        }
        $map['pid'] =   $pid;
        $list=   M("Item_category")->where($map)->field(true)->order('id asc')->select();
        int_to_string($list,array('hide'=>array(1=>'是',0=>'否')));
        $this->meta_title = '商品类别列表';
        $this->assign('list',$list);
        $this->display();
    }


    public function toogleDev($id,$value = 1){
        $this->editRow('Item_category', array('hide'=>$value), array('id'=>$id));
    }

    public function add()
    {
        if(IS_POST){
            $Item_category = D('Item_category');
            $data = $Item_category->create();
            if($data){
                $id = $Item_category->add();
                if($id){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_item_category', 'Item_category', $id, UID);
                    $this->success('新增成功', U('index',array('pid'=>I('pid'))));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Item_category->getError());
            }
        } else {
            $this->assign('info',array('pid'=>I('pid')));
            $category = M('Item_category')->field(true)->select();
            $category = D('Common/Tree')->toFormatTree($category,$title='category_name',$pk='id',$pid = 'pid',$root=0);
            $category = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级类别')), $category);
            $this->assign('Category', $category);
            $this->meta_title = '新增商品类别';
            $this->display('edit');
        }
    }

    /**
     * 编辑商品类别
     * @author zhoutonglei
     */
    public function edit($id = 0){
        if(IS_POST){
            $Item_category = D('Item_category');
            $data = $Item_category->create();
            if($data){
                if($Item_category->save()!== false){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_item_category', 'Item_category', $id, UID);
                    $this->success('更新成功', U('index',array('pid'=>I('pid'))));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Item_category->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Item_category')->field(true)->find($id);
            $category = M('Item_category')->field(true)->select();
            $category = D('Common/Tree')->toFormatTree($category,$title='category_name',$pk='id',$pid = 'pid',$root=0);
            $category = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $category);
            $this->assign('Category', $category);
            if(false === $info){
                $this->error('获取商品类别信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑商品类别';
            $this->display();
        }
    }


    /**
     * 删除商品类别
     * @author zhoutonglei
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Item_category')->where($map)->delete()){
            // S('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_menu', 'Menu', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }


}