<?php
namespace Admin\Controller;
use Think\Controller;
class FiremessageController extends Controller {
    public function index(){
            //最高权限查看所有部门
        if ($_SESSION['level']==1) {
            //var_dump($_SESSION);
            $data=M('depart')->where('level=1')->order('id')->select();
            $left_list=M('depart')->where('level=0')->order('id')->select();
            
            
        }else{
            $where['id']=$_SESSION['userID'];
            
            $left_list=M('depart')->where($where)->order('id')->select();//最高级
            //var_dump($left_list);
            $data=M('depart')->where('level=1')->order('id')->select(); //第二级
            
        }
        $data2=array();
        //通过遍历两次 的方式 将菜单的层次通过多维数组的层次表现出来  也可以用关联查询的方式
        foreach ($left_list as $key => $value) {
            
            foreach ($data as $key1 => $value1) {
                if ($value['id']==$value1['pid']) {
                    $value['next'][]=$value1;
                }
            }
            $data2[]=$value;      //左侧导航
            
       
        }
        $this->assign('data2',$data2);
        
        
        $this->display();
    }
    public function message_list(){

        $depart1=D('Depart1')->where('flag=0')->relation(true)->select();

        $extinguisher=M('Extinguisher');
        $where['discarde']=array('NEQ',0);
        $data=$extinguisher->where($where)->select();
        $this->assign('wholeFire',$data);
        $this->assign('list',$depart1);
        $this->display();
    }
    //三级管理员申请结果
    public function apply_list(){
        $depart1=M('Depart1')->where('pid='.$_SESSION['userID'])->order('create_time desc')->select();
        $extinguisher=M('Extinguisher');
        
        $where['change_status']=array('NEQ',0);
        $where['group_id']=$_SESSION['id'];
        $count=$extinguisher->where($where)->count();
        $Page =getpage($count,10);      //引用自定义函数 定义分页样式
        $show = $Page->show();//  分页显示输出
        $list=$extinguisher->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('change_status,die_date')->select();
        $this->assign('page',$show);
        $this->assign('wholeFire',$list);// 赋值数据集 

        //$this->display();
        $this->assign('list',$depart1);
        $this->display();
    }
    //二级管理员查看三级管理员的申请
    public function apply_notice(){
        $extinguisher=M('Extinguisher');
        $where['change_status']=1;
        $count=$extinguisher->where($where)->count();
        $Page =getpage($count,10);      //引用自定义函数 定义分页样式
        $show = $Page->show();//  分页显示输出
        $list=$extinguisher->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('wholeFire',$list);// 赋值数据集 

        $this->display();
    }
    public function agree(){

        $data=M('Depart1')->field('type')->where('id='.I('id'))->find();
        if ($data['type']==0) {
            
            $depart1=M('Depart1')->field('type,flag,create_time,id',true)->where('id='.I('id'))->find();
            $depart=M('Depart')->add($depart1);
        }else{
            $depart1=M('Depart1')->field('type,flag,create_time',true)->where('id='.I('id'))->find();
            $depart=M('Depart')->save($depart1);
        }
        $data1['id']=I('id');
        $data1['flag']=1;
        M('Depart1')->save($data1);

        $this->redirect('message_list');
    }
    //不同意 则修改对应的flag
    public function disagree() {

        $data1['id']=I('id');
        $data1['flag']=2;
        M('Depart1')->save($data1);
        $this->redirect('message_list');

        
    }
    // 同意 报废
    public function agree_discarde(){

        $stock=M('Stock');
        $extinguisher=M('Extinguisher');
        $where['qs_id']=I('id');
        
        $data['state']=3;
        $data1['discarde']=2;

        if ($stock->where($where)->save($data) && $extinguisher->where($where)->save($data1)) {
            $this->redirect('message_list');
        }


        $this->redirect('message_list');
    }
    
}