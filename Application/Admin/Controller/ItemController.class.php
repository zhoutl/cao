<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-06
 * Time: 9:42
 */

namespace Admin\Controller;
vendor('Code.Phpqrcode','' , '.php'); //引用Vendor目录下的phpSpi目录下的QueryList.class.php文件

class ItemController extends AdminController
{
    public function _initialize(){
        $this->crowd=array('1'=>'女性','2'=>'男性','3'=>'中性','4'=>'儿童');
        parent::_initialize();
    }

    public function index(){
       //查询条件过滤
        $item_name      =   trim(I('item_name'));
        $style_id      =   intval(I('style_id'));
        $sc_material      =   intval(I('material'));
        $sc_purity      =   intval(I('purity'));
        $crowd      =   intval(I('crowd'));
        $start_time     =  strtotime(I('start_time'));
        $end_time     =  strtotime(I('end_time'));
        $min_weight     =  round(intval(I('min_weight')),2);
        $max_weight     =  round(intval(I('max_weight')),2);
        if($item_name){
            $map['item_name'] = array('like',"%{$item_name}%");
            $this->assign('item_name',$item_name);
        }
        if($crowd){
            $map['crowd'] = $crowd;
            $info['crowd']=$crowd;
        }
        if($style_id){
            $map['style_id'] = $style_id;
            $info['style_id']=$style_id;
        }
        if($sc_material){
            $map['material'] = $sc_material;
            $info['material']=$sc_material;
        }
        if($sc_purity){
            $map['purity'] = $sc_purity;
            $info['purity']=$sc_purity;
        }

        if($min_weight >= 0 && $max_weight > 0 && $min_weight <= $max_weight){
            $map['weight'] = array('between',array($min_weight,$max_weight));
        }
        if($start_time > 0 && $start_time > 0 && $start_time <= $end_time){
            $map['add_time'] = array('between',array($start_time,$end_time));
        }


        $this->assign('info',$info);
        //分页信息
        $list = $this->lists('Item', $map,'add_time,id desc');
        foreach($list as $k=>$v){
            if($v['pic']!=''){
                $picture=explode(',',$v['pic']);
                $list[$k]['pic']=$picture[0];
            }
            $style=D('Dictionary')->field('title')->find($v['style_id']);
            $list[$k]['style']=$style['title'];
            $list[$k]['add_time']=date('Y-m-d H:i',$v['add_time']);
            if($v['weight']>0){
                $list[$k]['unit']='克（'.$v['weight'].'）';
            }else{
                $list[$k]['unit']='件';
            }
            $sell_number= D('OrderItem')->get_sell($v['id']);
            $list[$k]['stock']=$v['stock']-$sell_number;
            $list[$k]['crowd_title']=$this->crowd[$v['crowd']];
           // $material=D('Dictionary')->field('title')->find($v['material']);
            $purity=D('Dictionary')->field('title')->find($v['purity']);
            $list[$k]['material']=$purity['title'];
        }
        if($list) {
            $this->assign('list',$list);
        }
        //搜索列表信息
        $materials = M('Dictionary')->where("category='material'")->field(true)->select();
        $materials = array_merge(array(0=>array('id'=>0,'title'=>'材质')), $materials);
        $this->assign('materials',$materials);
        $style = M('Dictionary')->where("category='style'")->field(true)->select();
        $style = array_merge(array(0=>array('id'=>0,'title'=>'商品款式')), $style);
        $this->assign('style', $style);

        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->meta_title = '商品列表';
        $this->display();
    }

    /**
     * 新增商品
     * @author zhoutonglei
     */
    public function add(){
        if(IS_POST){
            $data=I('post.');
            $result=D('Item')->addItem($data);
            if($result['status']){
                    $info_description['item_id']=$result['id'];
                    $info_description['description']=I('post.description');
                    $description=D('ItemDetail');
                    if($description->create($info_description)){
                        $description->add();
                    }else{
                        $this->error($description->getError());
                    }
                $this->success('新增成功', Cookie('__forward__'));
            }else{
                $this->error($result['msg']);
            }

        }else{
            /* 获取数据 */
            $materials = M('Dictionary')->where("category='material'")->field(true)->select();
            $materials = array_merge(array(0=>array('id'=>0,'title'=>'材质')), $materials);
            $this->assign('materials',$materials);
            $style = M('Dictionary')->where("category='style'")->field(true)->select();
            $style = array_merge(array(0=>array('id'=>0,'title'=>'商品款式')), $style);
            $this->assign('style', $style);
            $this->meta_title = '新增商品';
            $this->display('edit');
        }
    }


    /**
     * 编辑商品
     * @author zhoutonglei
     */
    public function edit($id = 0){
        if(IS_POST){
            $data=I('post.');
            $result=D('Item')->editItem($data);
            if($result['status']){
                    $data['description']=I('post.description');
                    $description=D('ItemDetail');
                    $description->where('item_id='.$id)->save($data);
                $this->success('更新成功', Cookie('__forward__'));
            }else{
                $this->error($result['msg']);
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Item')->field(true)->find($id);
            $description=D('ItemDetail')->where("item_id=".$id)->field(true)->find();
            $info['description'] =$description['description'];
            $materials = M('Dictionary')->where("category='material'")->field(true)->select();
            $this->assign('materials',$materials);
            $style = M('Dictionary')->where("category='style'")->field(true)->select();
            $style = array_merge(array(0=>array('id'=>0,'title'=>'商品款式')), $style);
            $this->assign('style', $style);
            if(false === $info){
                $this->error('获取商品信息错误');
            }
            if($info['pic']){
                $picture=explode(',',$info['pic']);
                $pic=array();
                for($i=1;$i<=count($picture);$i++){
                    $pic[]=array('id'=>$i,'path'=>$picture[$i-1]);
                }
                $this->assign('picture', $pic);
            }
            $sell_number= D('OrderItem')->get_sell($id);
            $info['stock']=$info['stock']-$sell_number;
            $this->assign('info', $info);
            $this->meta_title = '编辑商品信息';
            $this->display();
        }
    }

    /**
     * 删除后台商品
     * @author zhoutonglei
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Item')->where($map)->delete()){
            // S('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_item', 'Item', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
    /**
     * 商品详情
     * @author zhoutonglei
     */
    public function detail()
    {
        $item=D('Item')->find(intval(I('id')));
        $purity = M('Dictionary')->find($item['purity']);
        $item['purity']=$purity['title'];
        $style = M('Dictionary')->find($item['style_id']);
        $item['style']=$style['title'];
        $item['crowd']=$this->crowd[$item['crowd']];
        if($item['pic']!='') {
            $array_img=explode(',',$item['pic']);
            for ($i = 1; $i <= count($array_img); $i++) {
                $pic[] = array('id' => $i, 'path' => $array_img[$i - 1]);
            }
        }
        $this->assign('picture',$pic);
        $description=D('ItemDetail')->where("item_id=".$item['id'])->field(true)->find();
        $item['description']=$description['description'];
        $sell_number= D('OrderItem')->get_sell(intval(I('id')));
        $item['stock']=$item['stock']- $sell_number;
        $this->assign('data',$item);
        $this->meta_title = '商品详细信息';
        $this->display();
    }
    /**
     * 商品款式列表
     * @author zhoutonglei
     */
    public function style_index()
    {
        $title=I('get.title');
        if($title){
            $map['title']=array('like',"%{$title}%");
        }
        $map['category']='style';
        $list = $this->lists('Dictionary', $map,'id desc');
        if($list){
            $this->assign('list',$list);
        }
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->meta_title = '商品款式管理';
        $this->display('style_index');
    }
    /**
     * 新增商品款式
     * @author zhoutonglei
     */
    public function style_add()
    {
        if(IS_POST){
            $data['title']=str_check(I('post.title'));
            $data['category']='style';
            while(true){
                if(empty($data['title'])){
                    $this->error('请填写款式名称');
                    break;
                }
                if(!(D('Dictionary')->create($data))){
                    $this->error( D('Dictionary')->getError());
                    break;
                }
                D('Dictionary')->add();
                $this->success('新增成功', Cookie('__forward__'));
                break;
            }
        }else{
            $this->meta_title = '新增商品款式';
            $this->display('style_edit');
        }
    }
    /**
     * 编辑商品款式
     * @author zhoutonglei
     */
    public function style_edit($id=0)
    {
        if(IS_POST){
            $data['title']=str_check(I('post.title'));
            $data['category']='style';
            $data['id']=intval($id);
           while(true){
               if(empty($data['title'])){
                   $this->error('请填写款式名称');
                   break;
               }
               if(!(D('Dictionary')->create($data))){
                   $this->error( D('Dictionary')->getError());
                   break;
               }
               D('Dictionary')->save();
               $this->success('编辑成功', Cookie('__forward__'));
               break;
           }
        }else{
            $dictionary=D('Dictionary')->field(true)->find(intval($id));
            $this->assign('info',$dictionary);
            $this->meta_title = '编辑商品款式';
            $this->display('style_edit');
        }
    }
    /**
     * 删除商品款式
     * @author zhoutonglei
     */
    public function style_del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Dictionary')->where($map)->delete()){
            // S('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_dictionary', 'Dictionary', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    public function getpurity(){
        if(IS_AJAX){
            $pid=intval(I('pid'));
            $puritys = M('Dictionary')->where("pid=$pid and category='purity'")->field(true)->select();
            if($puritys){
                $data=array('status'=>1,'data'=>$puritys);
            }else{
                $data=array('status'=>0,'data'=>null);
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 根据条件获取商品信息
     * @author zhoutonglei
     */
    public function getItems(){
        if(IS_AJAX){
            $item_name=trim(I('search_name'));
            $artno=trim(I('artno'));
            if($item_name)
               $map['item_name']=array('like',"%{$item_name}%");
            if($artno){
                $array_artno=explode(',',$artno);
                if(count($array_artno)>0){
                    $map['artno']=array('in',$array_artno);
                }else{
                    $map['artno']=$artno;
                }
            }
            $items = M('Item')->where($map)->field(true)->select();
            foreach($items as $k=>$v){
                $sell_number= D('OrderItem')->get_sell($v['id']);
                $items[$k]['stock']=$v['stock']-$sell_number;
            }
            if($items){
                $data=array('status'=>1,'data'=>$items);
            }else{
                $data=array('status'=>0,'data'=>null);
            }
            $this->ajaxReturn($data);
        }

    }
    public function aa(){

     print_r($_SERVER['HTTP_HOST'].U('Home/index/aa'));
    }


}