<?php

namespace Admin\Controller;

use Think\Controller;

header('Content-type:text/html;charset=utf-8');

class AdminController extends BaseController {

	public function admin_list() {
		$admin=M('amd')->order('id desc')->select();;
		$this -> assign('list', $admin);
		$this -> display();

	}
	
	//添加链接
    public function add_admin(){
     
        $this->display();
    }

    public function do_addAdmin()
    {
    	if(!IS_AJAX)
        {
    		$data['info']='页面不存在!';
            $data['status']=0;
    	}
        else
        {
    		$amd=M('amd');
            $data1['username']=trim(I('post.username', '', 'htmlspecialchars'));

            
            $data1['pod']  = md5($_POST['pd']);
            // $data1['birthdate']  = strtotime($data1['birthdate']);
            $data1['userimg']=trim(I('post.userimg', '', 'htmlspecialchars'));
            // $data1['email']=trim(I('post.email', '', 'htmlspecialchars'));
            // $data1['phone']=trim(I('post.phone', '', 'htmlspecialchars'));
            // $data1['address']=trim(I('post.address', '', 'htmlspecialchars'));
            // $data1['note']=trim(I('post.note', '', 'htmlspecialchars'));
            $data1['add_time']=time();
            $data1['last_loginip']=get_client_ip();

            if ($amd->add($data1)) {
				$data=array(
                    'url'=> U('admin_list'),
                    'info'=>'添加成功!',
                    'status'=>1
                );
            }else{
                $data=array(
                    'info'=>'添加失败!',
                    'status'=>0
                );
            
			}			                 
			$this->ajaxReturn($data);
    		
    	}
    }
    

	//编辑链接
	public function edit_link() {
		
		$detail = M('Link')->where(array('id' => I('id')))->find();
        $this->assign('detail', $detail);
        $this->display();
		
	}

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

	//删除链接
	public function del() {

		M('Link')->where(array('id' => I('id')))->delete();
        $this->redirect('link_list');
	}

}
