<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
    	$feed=M('Feedback')->where('flag=0')->select();
    	$link=M('Link')->where('flag=0')->select();
    	$this->assign('link',$link);
    	$this->assign('feed',$feed);
        $this->display();
    }
    public function reply(){
    	if (!IS_AJAX) {
            $data['info']='小伙子别干坏事啊!';
            $data['status']=0;
        }else{
            

            $feed=M('feedback');
            $id=I('id');
            $data1['c_name']=$_SESSION['username'];
            $data1['c_photo']=$_SESSION['userimg'];
            $data1['c_time']=time();
           	$data1['id']=$id;
           	$data1['flag']=1;
            $data1['c_content']=trim(I('content', '', 'htmlspecialchars'));

            if ($feed->save($data1)) {
                $data['info']='回复成功!';
                $data['status']=1;
                $data['url']=U("index");
            }else{
                $data['info']='回复失败!';
                $data['status']=0;
                
            }
            
        }
        $this->AjaxReturn($data);
    }

    public function del_feed(){
    	$id=I('id');
    	if(M('Feedback')->where('id='.$id)->delete()){
    		$this->redirect('index');
    	}else{
    		$this->redirect('index');
    	}
    	
    }

    public function link_ok(){
    	if (!IS_AJAX) {
            $data['info']='小伙子别干坏事啊!';
            $data['status']=0;
        }else{
            

            $link=M('link');
            $id=I('id');
            
            $data1['update_time']=time();
           	$data1['id']=$id;

           	$data1['flag']=1;
            if ($link->save($data1)) {
                $data['info']='通过成功!';
                $data['status']=1;
                $data['url']=U("index");
            }else{
                $data['info']='通过失败!';
                $data['status']=0;
                
            }
            
        }
        //echo I('id');
           	//exit();
        $this->AjaxReturn($data);
    }
	public function del_link() {

                M('Link')->where(array('id' => I('id')))->delete();
        $this->redirect('index');
        }


}
