<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }
    public function top(){
        switch ($_SESSION['level']) {
            case 1:
                
                $condition=1;   //1代表段领导并且点开了 段领导 菜单
                break;               
            case 2:
                
                $condition=2;  //2级管理员 材料科 

                break;

            case 3:
                
                $condition=3;   //3级管理员  科室
                 
                break;
            case 4:

                $condition=4;   // 4级管理员  班组
                break;

            
            default:
                # code...
                break;
        }
        //echo $condition;
        $this->assign('condition',$condition);
        // var_dump($_SESSION);
        // die();
        $this->assign('uname',$_SESSION['username']);
        $this->display();
    }
    public function left(){
    	
            //最高权限查看所有部门
        if ($_SESSION['level']==1 || $_SESSION['level']==2) {
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
    public function right(){
        
        $this->display();
    }
}