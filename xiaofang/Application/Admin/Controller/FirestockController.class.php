<?php
/**
 * "@Author 蒋晓辉　熊小飞",
 * "@DateTime 2016-12-24",
 * "@Copyright 西南交大 枫林157s",
 * "@License 版权所有，盗版必究",
 * "@Version 1.0",
 * "@Contact muruoqiu@163.com",

*/
  
namespace Admin\Controller;
use Think\Controller;
class FirestockController extends Controller {
    

    public function stock_manage(){
        $stock=M("Stock");
        // 报废品不出现在这里
        $where['state']=array('NEQ',3);
        $count=$stock->where($where)->count();
        $Page =getpage($count,10);      //引用自定义函数 定义分页样式
        $show = $Page->show();//  分页显示输出
        $list=$stock->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('die_date')->select();

        $depart=M('Depart');
        foreach ($list as $key => $value) {
            $die_date=strtotime($value['die_date']);
            $data[$key]=$value;
            $data1=$depart->field('name')->where(array('id'=>$value['group_id']))->find();
            $data[$key]['group_id']=$data1['name'];
            if ($die_date<time()) {
                
                $data[$key]['status']=0;    //0 表示过期

            }else{
                $differ=($die_date-time())/86400;
                if ($differ<=7) {
                    $data[$key]['status']=1;    //1 表示7天内过期
                }else if ($differ<=30) {
                    $data[$key]['status']=2;    //2 表示30天内过期
                }else{
                    $data[$key]['status']=3;    //3 表示未过期过期
                }
            }
            
        }
        //var_dump($data);
        //var_dump($list);
        //die();
        $this->assign('page',$show);
        $this->assign('wholeFire',$data);// 赋值数据集

         //统计功能
        $total['all']=$stock->count();
        $total['use']=$stock->where(array('use'=>1))->count();
        $total['nouse']=$total['all']-$total['use'];
        $total['new']=$stock->where(array('state'=>0))->count();
        $total['need_rep']=$stock->where(array('state'=>1))->count();
        $total['rep']=$stock->where(array('state'=>2))->count();
        $total['useless']=$stock->where(array('state'=>3))->count();

        // var_dump($total);
        // die();
        $this->assign('total',$total);   
        $this->display();
    }
    public function doNull($post){
        //数据为空验证
            foreach ($post as $key => $value) {
                if (empty($value)) {
                    $this->error('请填写完整数据！');
                }
            }
    }
    public function do_addStock(){
        //id    QS_id depart_id workshop_id  group_id   res_person  responsible_person  variety type pro_date    during_time die_date    place  stock check_date    pid 
        //判断是否填写完整操作    
        $this->doNull(I('post.'));

        //判断QS码是否填写操作
        //$s=M('Stock');
        $m=M('Stock');
        //$old=$m->where("QS_id='%s'",I('post.qs'))->find();
        //数据库写入操作
        


        // $m->depart_id=I('post.depart');
        // $m->workshop_id=I('post.workshop');
        // $m->group_id=I('post.group');
        // $m->res_person=I('post.res');
        $start=strtotime(I('post.pro_date'));//生产日期时间戳
        $during=I('post.during_time');//维保时间（年）
        
        $die=$start+$during*365*24*60*60;//过期时间
        $data['qs_id']=I('post.qs');
       
        $data["variety"]=I('post.variety');
        $data["type"]=I('post.type');
        $data["pro_time"]=I('post.pro_date');
        $data["during_time"]=I('post.during_time');
        $data["die_date"]=date('Y-m-d',$die);
        $data['use']=0;

        
        // $m->place=I('post.place');
        // $m->stock=I('post.stock');
        //$m->check_date=date('Y-m-d H:m:s',time());
        $where['qs_id']=$data['qs_id'];

        //库存在该sq码 说明是返修品
        if($m->where($where)->find()){
            $data['state']=2;
            $data['rep_date']=date('Y-m-d',time()); //返修品维保日期为信息录入时间
            if ($m->where($where)->save($data)) {
                $this->success('录入成功！');
            }else{
                $this->error('录入失败！');
            }

            
        }else{
            if ($m->add($data)) {
                $this->success('录入成功！');
            }else{
                $this->error('录入失败！');
            }
            
            
        }
        


    }
    public function add_extinguisher(){
        $this->display();
    }
    public function do_addextinguisher(){
        //id    QS_id depart_id workshop_id  group_id   res_person  responsible_person  variety type pro_date    during_time die_date    place  stock check_date    pid 
        //判断是否填写完整操作    
        $this->doNull(I('post.'));

        //判断QS码是否填写操作
        $qs_id=I('post.qs');
        $stock=M('Stock');
        $extinguisher=M('Extinguisher');
        $where['qs_id']=$qs_id;
        $where['use']=0;
        $where1['qs_id']=$qs_id;
        $data1=$stock->where($where)->find();
        //查看是否是返修品
        $data2=$extinguisher->where($where1)->find();
        if (!$data1) {
            $this->error("库存没有录入该灭火器信息!请录入后再使用!");
        }else if ($data2) {
            $data=$data1;
            $data['group_id']=$_SESSION['id'];  //直接隶属的部门
            $data['res_person']=I('post.res');
            $data['place']=I('post.place');
            $data['change_status']=0;
            unset($data['id']);
            unset($data['use']);
            unset($data['state']);
            if ($extinguisher->where($where1)->save($data)) {
                $data1['use']=1;
                if ($stock->save($data1)) {
                    $this->success('配置成功！');
                }else{
                    $this->error('配置失败,请重试！');
                }

            }
        }
        else{
            $data=$data1;
            $data['group_id']=$_SESSION['id'];  //直接隶属的部门
            $data['res_person']=I('post.res');
            $data['place']=I('post.place');
            unset($data['id']);
            unset($data['use']);
            unset($data['state']);


            if ($extinguisher->add($data)) {
                $data1['use']=1;
                if ($stock->save($data1)) {
                    $this->success('配置成功！');
                }else{
                    $this->error('配置失败,请重试！');
                }

            }
            
        }
        
    }

    //申请更换时,表里的相应字段记录
    public function apply_change(){
        if (!IS_AJAX) {
            $data['info']='错误操作!';
            $data['status']=0;
        }else{
            $extinguisher = M('Extinguisher'); // 实例化User对象
            $where['qs_id'] = trim(I('get.id', '', 'htmlspecialchars'));
            $data1['change_status']=1;
            $u=$extinguisher->where($where)->save($data1);
                
            if($u){
                    
                    
                    $data['info']='申请提交成功,请等待审核!';
                    $data['status']=1;
                    //$data['url']=U("Fireinfo/index");
                }else{
                    
                    $data['info']='申请提交失败,请勿重复提交申请!';
                    $data['status']=0;
                    //$data['url']=U("index");         //
                }
            //}
            


        }
        //var_dump($data);
        $this->AjaxReturn($data);
    }
    //班组申请更换灭火器
    public function apply_result(){
        
        $extinguisher = M('Extinguisher'); // 实例化User对象
        //正常访问,非查询
        //echo $_SESSION['id'];
        //var_dump($_POST);
        switch ($_SESSION['level']) {
            case 1:
                // if ($_SESSION['id']==1)
                //     //$condition=1;   //1代表段领导并且点开了 段领导 菜单
                // else
                    $where['group_id']=$_SESSION['id'];
                    //$condition=11;
                break;
            case 2:
                if ($_SESSION['id']!=1){
                    $where['group_id']=$_SESSION['id'];//2级管理员(材料科)点击非段领导按钮
                    //$condition=22;
                }

                if ($_SESSION['id']==4) {
                    //$condition=2;  //材料科 点开材料科按钮
                }
                
                break;

            case 3:
                
                    $where['group_id']=$_SESSION['id'];
                    //$condition=3;
                
                break;
            case 4:
                    $where['group_id']=$_SESSION['id'];
                    //$condition=4;
                break;

            
            default:
                # code...
                break;
        }
        
        //var_dump($where);
        $where['change_status']=array('neq',0);
        $depart=M('Depart');
        $count=$extinguisher->where($where)->count();
        $Page =getpage($count,10);      //引用自定义函数 定义分页样式
        $show = $Page->show();//  分页显示输出
        $list=$extinguisher->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('die_date')->select();

        
        
        //var_dump($data);
        //var_dump($list);
        //die();
        $this->assign('page',$show);
        $this->assign('wholeFire',$list);// 赋值数据集 

        $this->display();
        
    }
    
    //申请更换时,表里的相应字段记录  申请报废
    public function apply_discarde(){
        if (!IS_AJAX) {
            $data['info']='错误操作!';
            $data['status']=0;
        }else{
            $extinguisher = M('Extinguisher'); // 实例化User对象
            $where['qs_id'] = trim(I('get.id', '', 'htmlspecialchars'));
            $data1['discarde']=1;
            $u=$extinguisher->where($where)->save($data1);
                
            if($u){
                    
                    
                    $data['info']='申请提交成功,请等待审核!';
                    $data['status']=1;
                    //$data['url']=U("Fireinfo/index");
                }else{
                    
                    $data['info']='申请提交失败,请勿重复提交申请!';
                    $data['status']=0;
                    //$data['url']=U("index");         //
                }
            //}
            


        }
        //var_dump($data);
        $this->AjaxReturn($data);
    }
    //查看 更换通知和 报废通知
    public function apply_notice(){
        
        $extinguisher = D('Extinguisher'); // 实例化User对象
        //正常访问,非查询
        //echo $_SESSION['id'];
        //var_dump($_POST);
        
        //var_dump($where);
        //$where['qs_id']=I('get.id')
        $where1['change_status']=array('NEQ',0);
        //$depart=M('Depart');
        //$count=$extinguisher->where($where)->count();
        //$Page =getpage($count,10);      //引用自定义函数 定义分页样式
        //$show = $Page->show();//  分页显示输出
        $list1=$extinguisher->where($where1)->order('change_status,die_date')->relation(true)->select();
        $where2['discarde']=array('NEQ',0);

        $list2=$extinguisher->where($where2)->order('die_date')->relation(true)->select();

        
        
        //var_dump($list2);
        //var_dump($list);
        //die();
        $this->assign('page',$show);
        $this->assign('wholeFire',$list1);// 赋值数据集
        $this->assign('ext',$list2); 

        $this->display();
        
    }
    //同意申请更换操作
    public function agree_change(){
        
        $extinguisher = M('Extinguisher'); // 实例化User对象
        $stock=M('Stock');
        $where['qs_id'] = trim(I('get.id', '', 'htmlspecialchars'));
        $data1['change_status']=2;
        $data2['use']=0;
        $data2['state']=1;
        $u=$extinguisher->where($where)->save($data1);
        $s=$stock->where($where)->save($data2);
            
        if($u && $s){
            $this->redirect('apply_notice'); 
        }else{
            $this->error('操作失败!请稍后重试.');
        }
        
        
    
    }
   
}
?>