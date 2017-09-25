<?php

namespace Admin\Controller;

use Think\Controller;

header('Content-type:text/html;charset=utf-8');

class SaidController extends BaseController {


	public function said_list() {
		
		$said = M('Said');		
		$count = $said -> count();
		$page = new \Think\Page($count,10);      //  实例化分页类 传入总记录数和每页显示的
		$show = $page->show();//  分页显示输出
		
		$list = $said->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
		
		$this -> assign('list', $list);
		$this -> assign('page', $show);
		$this -> display();

	}


    public function add_said(){
     
        $this->display();
    }


    public function do_addSaid()
    {
        if (!IS_AJAX) {
            $data['info']='页面不存在!';
            $data['status']=0;
        } else {

			$create_time = strtotime(date("Y-m-d H:i:s", time()));
			$data1['s_content'] = $_POST['s_content'];		
			$data1['create_time'] = $create_time;		
			//$data1['s_from'] = getOS();
			//$data1['s_img'] = session('admin.userimg');	
			$data1['s_writer'] = trim(I('post.s_writer', '', 'htmlspecialchars'));
			$data1['s_img']=$_SESSION['userimg'];
			$data1['s_view'] = trim(I('post.s_view', '', 'htmlspecialchars'));
			$data1['create_ip'] = get_client_ip();

			if (M('Said')->add($data1)) {
				$data=array(
                    'url'=> U('said_list'),
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


	public function edit_said() {
		
		$detail = M('Said')->where(array('s_id' => I('s_id')))->find();
        $this->assign('detail', $detail);
        $this->display();
		
	}

	public function do_editSaid() {

		$s_id = I('s_id', '', htmlspecialchars);
		
		if (!IS_AJAX) {
            $data['info']='页面不存在!';
            $data['status']=0;
        } else {

			
			$data1['s_content'] = $_POST['s_content'];		
				
			//$data1['s_from'] = getOS();
			//$data1['s_img'] = session('admin.userimg');	
			$data1['s_writer'] = trim(I('post.s_writer', '', 'htmlspecialchars'));
			$data1['s_view'] = trim(I('post.s_view', '', 'htmlspecialchars'));
			$data1['create_ip'] = get_client_ip();

			if (M('Said')->where('s_id='.$s_id)->save($data1)) {
				$data=array(
                    'url'=> U('said_list'),
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

    public function del() {

        
        M('said')->where(array('s_id' => I('id')))->delete();
        $this->redirect('said_list');

    }

}
