<?php
namespace Admin\Controller;
use Think\Controller;
class SysController extends Controller {

    public function admin_rule(){
        $admin_rule=M('auth_rule')->order('sort')->select();
        // var_dump($data2);
        //  exit;
        $arr=classify($admin_rule);      //右侧菜单列表
         $this->assign('list',$arr);
        // $this->assign('left_list',$data2);
        $this->display();
    }
    
    public function admin_rule_add(){
    	
        if (!IS_AJAX) {
            $data=array(
                'info'=>'页面不存在!',
                'status'=>0,
                );
        }else{
            $admin_rule=M('auth_rule');
            $data1=array(
                'name'=>I('name'),
                'title'=>I('title'),
                'status'=>I('status'),
                'sort'=>I('sort'),
                'addtime'=>time(),
                'pid'=>I('pid'),
                'level'=>I('level'),
                
            );
            if ($admin_rule->add($data1)) {
                $data=array(
                    'url'=> U('admin_rule'),
                    'info'=>'添加成功!',
                    'status'=>1
                );
            }else{
                $data=array(
                    'info'=>'添加失败!',
                    'status'=>0
                );
            }
            
        }

        $this->ajaxReturn($data);    //调用ajaxReturn方法 默认返回Jason数据

    }

    
            

        
}