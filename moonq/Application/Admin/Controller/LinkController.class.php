<?php

namespace Admin\Controller;

use Think\Controller;

header('Content-type:text/html;charset=utf-8');

class LinkController extends BaseController {

	public function link_list() {
		$link=M('Link');
		$count = $link->count();

		$page = new \Think\Page($count,10);      //  实例化分页类 传入总记录数和每页显示的
		$show = $page->show();//  分页显示输出
		
		$list = $link->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		
		$this -> assign('list', $list);
		$this -> assign('page', $show);
		$this -> display();

	}
	
	//添加链接
    public function add_link(){
     
        $this->display();
    }

    public function do_addLink()
    {
    	if (!IS_AJAX) {
            $data['info']='页面不存在!';
            $data['status']=0;
        } else {

			$create_time = strtotime(date("Y-m-d H:i:s", time()));
            $data1['name'] = trim(I('post.name', '', 'htmlspecialchars'));
            $data1['url'] = trim(I('post.url', '', 'htmlspecialchars'));
			$data1['status'] = trim(I('post.status', '', 'htmlspecialchars'));
			$data1['orderby'] = trim(I('post.orderby', '', 'htmlspecialchars')); 
			$data1['create_time'] = $create_time; 
			$data1['update_time'] = $create_time;

			if (M('link')->add($data1)) {
				$data=array(
                    'url'=> U('link_list'),
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
