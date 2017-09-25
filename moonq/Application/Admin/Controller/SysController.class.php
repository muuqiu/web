<?php
namespace Admin\Controller;
use Think\Controller;
class SysController extends BaseController {

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
            // $flag=I('flag');
            // echo $flag;
            //exit();
            if (I('flag')==0) {             //falg为0 代表是增加菜单
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
            }else if (I('flag')==1) {   //falg为1 代表是修改菜单
                $admin_rule=M('auth_rule');
                $data1=array(
                    'id'=>I('id'),
                    'name'=>I('name'),
                    'title'=>I('title'),
                    'status'=>I('status'),
                    'sort'=>I('sort'),
                    //'addtime'=>time(),
                    'pid'=>I('pid'),
                    'level'=>I('level'),
                    
                );
                if ($admin_rule->save($data1)) {
                    $data=array(
                        'url'=> U('admin_rule'),
                        'info'=>'修改成功!',
                        'status'=>1
                    );
                }else{
                    $data=array(
                        'info'=>'修改失败!',
                        'status'=>0
                    );
                }
            }
            
            
        }

        $this->ajaxReturn($data);    //调用ajaxReturn方法 默认返回Jason数据

    }
    public function edit_menu(){
        
        if (!IS_AJAX) {
            $data=array(
                'info'=>'页面不存在!',
                'status'=>0,
                );
        }else{
            $id=$_GET['id'];


            if ($admin_rule=M('auth_rule')->where('id='.$id)->find()) {
                $data=$admin_rule;
                $data['status']=1;
            }else{
                $data['status']=0;
            }
            
        
        }
       //var_dump($data);
       //exit();
        $this->ajaxReturn($data);    //调用ajaxReturn方法 默认返回Jason数据

    }
    public function del_menu() {

        M('auth_rule')->where(array('id' => I('id')))->delete();
        $this->redirect('admin_rule');
    }

    //轮播管理
    public function banner(){
        $banner=M('Banner')->select();

        $this->assign('list',$banner);
        $this->display();
    }
    public function add_banner(){
        
        if (!IS_AJAX) {
            $data=array(
                'info'=>'页面不存在!',
                'status'=>0,
                );
        }else{
            $data1['status']=I('status');
            $data1['img']=I('photo');


            if (M('banner')->add($data1)) {
                
                $data=array(
                'info'=>'添加成功!',
                'status'=>1,
                'url'=>U('banner'),
                );
            }else{
                $data=array(
                'info'=>'添加失败!',
                'status'=>0,
                
                );
            }
            
        
        }
       //var_dump($data);
       //exit();
        $this->ajaxReturn($data);    //调用ajaxReturn方法 默认返回Jason数据

    }
    public function del_banner() {

        M('banner')->where(array('id' => I('id')))->delete();
        $this->redirect('banner');
    }


    
            

        
}