<?php
namespace Admin\Controller;
use Think\Controller;
class FiredepartController extends Controller {
    // public function index(){
        
    // }
    public function depart_list(){

        $depart=M('depart')->where('istwo=1')->select();
        $this->assign('list',$depart);
        $this->display();
    }
    public function add_depart(){
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
                $depart=M('depart');
                $data1=array(
                    'name'=>I('name'),
                    
                    'sort'=>I('sort'),
                    'istwo'=>1,
                   
                    
                );
                if ($depart->add($data1)) {
                    $data=array(
                        'url'=> U('depart_list'),
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
                $depart=M('depart');
                $data1=array(
                    'id'=>I('id'),
                    'name'=>I('name'),
                    
                    
                    'sort'=>I('sort'),
                    //'addtime'=>time(),
                   
                    
                );
                if ($depart->save($data1)) {
                    $data=array(
                        'url'=> U('depart_list'),
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
    public function edit_depart(){
        $data=M('Depart')->where('id='.I('id'))->find();
        $data['status']=1;
        $this->ajaxReturn($data);
    }
    public function del_depart() {

        M('depart')->where(array('id' => I('id')))->delete();
        $this->redirect('depart_list');
    }
    
}