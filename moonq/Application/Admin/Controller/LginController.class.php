<?php 
namespace Admin\Controller;
use Think\Controller;

class LginController extends Controller {
	public function index(){
	//	echo 'aa';
	//exit();		
		$this->display();
    }
    public function verify(){
    	$config=array(
			'fontSize'=>'20',
			'expire'=>10,           //10秒失效  
			'length'=>4,
			'useNoise'=>false,       // 关闭验证码杂点
			'useImgBg'=>true,           ////  开启验证码背景图片功能 随机使用 ThinkPHP/Library/Think/Verify/bgs  目录下面的图片
			'useCurve'=>false,   //不适用混淆线条
			'codeSet'=>'0123456789', //  设置验证码字符
			);
		$verify=new \Think\Verify($config);
		$verify->entry();
    }
    public function do_lgin(){
    	if (!IS_AJAX) {
    		$data['info']='用户不存在!';
            $data['status']=0;
    	}else{
    		$verify = new \Think\Verify();
			
			if (!($verify->check(I('code')))) {          //用自带的函数检测验证码是否正确
				$data['info']='验证码错误!';
				$data['status']=0;
            	$data['url']=U("index",array('id'=>rand(20,100)));  
			}else{
				$data1['username'] = trim(I('username', '', 'htmlspecialchars'));
	    		$data1['pod'] = md5(trim(I('password', '', 'htmlspecialchars')));
	    	
//		$data1['pod']=I('password');
	//	$data1['last_logintime']=time();
	$u=M('amd')->where($data1)->find();
	    		
	    		if($u){
	    			
	    			$data3['last_logintime']=time();
	    			$data3['last_loginip']=get_client_ip();
	    			$data3['id']=$u['id'];
	    			M('amd')->where()->save($data3);  //写入ip和时间

	    			//销毁并写入新session
	    			session(null);
	    			$_SESSION['userID']=$u['id'];
	    			$_SESSION['username']=$u['username'];
	    			$_SESSION['userimg']=$u['userimg'];
	    			//var_dump($_SESSION);
	    			//exit();
	    			$data['info']='登录成功!';
	            	$data['status']=1;
	            	$data['url']=U("Index/index");
	    		}else{
	    			
	    			$data['info']='用户或密码错误!';
	            	$data['status']=0;
	            	$data['url']=U("index",array('id'=>rand(20,100)));         //随机数 以清空页面缓存 让页面刷新
	    		}
			}
    		


    	}
		$this->AjaxReturn($data);
    }
    public function logout(){
    	session(null);
    	$this->redirect('Home/Index/index');
    }

}
 ?>
