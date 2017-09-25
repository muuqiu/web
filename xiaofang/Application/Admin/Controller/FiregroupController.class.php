<?php

namespace Admin\Controller;

use Think\Controller;

header('Content-type:text/html;charset=utf-8');

class FiregroupController extends Controller {

	public function group_list() {
		//$group=M('amduser')->order('id')->select();
        //查出所有部门,用于添加时指定用户部门
        $pid=$_SESSION['userID'];  //userID是用户的部门对应的id
        $depart=M('depart')->order('id')->where('pid='.$pid)->select();
        //$arr=classify($depart);   //调用自己编写的无限极分类方法,在 Application/Common/common里
        $this->assign('list',$depart);

		//$this -> assign('list', $admin);
        //var_dump($admin);
		$this -> display();

	}
	
	
    public function add_group()
    {
    	if(!IS_AJAX)
        {
    		$data['info']='页面不存在!';
            $data['status']=0;
    	}
        else{
            if (I('flag')==1) {  //修改
                $data1['type']=1;   //type字段表示申请类型
                $data1['id']=trim(I('post.id', '', 'htmlspecialchars'));

                                                                                   

            }else{
                $data1['type']=0;
            }

            $group=M('depart1');
            $data1['name']=trim(I('post.name', '', 'htmlspecialchars'));
            $data1['pid']=$_SESSION['userID'];
            $data1['sort']=trim(I('post.sort', '', 'htmlspecialchars'));
            $data1['create_time']=time();

            if ($group->add($data1)) {
                $data=array(
                    'url'=> U('group_list'),
                    'info'=>'提交申请成功,请等待审核',
                    'status'=>1
                );
            }else{
                $data=array(
                    'info'=>'提交申请失败,请重新提交',
                    'status'=>0
                );
            
            }
        }

            
                

        

    		

            
            //$data1['ppwwdd']  = md5($_POST['pd']);
            //$data1['level']=trim(I('post.level', '', 'htmlspecialchars'));
            //$data1['pid']=$_SESSION['userID'];
            //$data1['sort']=trim(I('post.sort', '', 'htmlspecialchars'));
            // $data1['birthdate']  = strtotime($data1['birthdate']);
            //$data1['userimg']=trim(I('post.userimg', '', 'htmlspecialchars'));
            // $data1['email']=trim(I('post.email', '', 'htmlspecialchars'));
            // $data1['phone']=trim(I('post.phone', '', 'htmlspecialchars'));
            // $data1['address']=trim(I('post.address', '', 'htmlspecialchars'));
            // $data1['note']=trim(I('post.note', '', 'htmlspecialchars'));
            //$data1['add_time']=time();
            //$data1['last_loginip']=get_client_ip();

            	                 
			$this->ajaxReturn($data);
    		
    }

    

	//编辑链接
	// public function edit_admin(){
        
 //        if (!IS_AJAX) {
 //            $data=array(
 //                'info'=>'页面不存在!',
 //                'status'=>0,
 //                );
 //        }else{
 //            $id=$_GET['id'];


 //            if ($admin_rule=M('amduser')->where('id='.$id)->find()) {
 //                $data=$admin_rule;
 //                $data['status']=1;
 //            }else{
 //                $data['status']=0;
 //            }
            
        
 //        }
 //       //var_dump($data);
 //       //exit();
 //        $this->ajaxReturn($data);    //调用ajaxReturn方法 默认返回Jason数据

 //    }

	public function do_editLink() {

		$id = I('id', '', htmlspecialchars);
		
		if (!IS_AJAX) {
            $data['info']='页面不存在!';
            $data['status']=0;
        } else {

			$update_time = strtotime(date("Y-m-d H:i:s", time()));
            $data1['name'] = trim(I('post.name', '', 'htmlspecialchars'));
            $data1['url'] = trim(I('post.url', '', 'htmlspecialchars'));
			$data1['status'] = trim(I('post.status', '', 'htmlspecialchars'));
			$data1['orderby'] = trim(I('post.orderby', '', 'htmlspecialchars')); 
			
			$data1['update_time'] = $update_time;

			if (M('link')->where('id='.$id)->save($data1)) {
				$data=array(
                    'url'=> U('link_list'),
                    'info'=>'修改成功!',
                    'status'=>1
                );
            }else{
                $data=array(
                    'info'=>'修改失败!',
                    'status'=>0
                );
            
			}			                 
			$this->ajaxReturn($data);
        
        
        }
	}

	public function edit_group(){
        $data=M('Depart')->where('id='.I('id'))->find();
        $data['status']=1;
        $this->ajaxReturn($data);
    }
    public function del_group() {
        //$id=$I('id');
        $arr=M('depart')->where(array('id' => I('id')))->find();
        $arr['type']=2;
        if(M('depart1')->add($arr)){
            $data['info']="成功提交申请,请等待审核.";
        }
        else{
            $data['info']="提交申请失败,请稍后重试!";
        }
        $this->ajaxReturn($data);
    }
}
