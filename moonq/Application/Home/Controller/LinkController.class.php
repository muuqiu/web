<?php
namespace Home\Controller;
header("Content-Type:text/html; charset=utf8");//命名空间后面
use Think\Controller;
class LinkController extends Controller {

    public function do_addLink(){
        if (!IS_AJAX) {
            $data['info']='小伙子别干坏事啊!';
            $data['status']=0;
        }else{
            
            $link=M('Link');
            $data1['create_time']=time();
            $data1['ip']=get_client_ip();
            $data1['name']=trim(I('name', '', 'htmlspecialchars'));
            $data1['url']=trim(I('url', '', 'htmlspecialchars'));
            $data1['content']=trim(I('content', '', 'htmlspecialchars'));
            $data1['status']=1;
            $data1['flag']=0;

            if ($link->add($data1)) {
                $data['info']='申请成功,请等待审核!';
                $data['status']=1;
                $data['url']=U("index");
            }else{
                $data['info']='抱歉,申请失败,请稍后重试!';
                $data['status']=0;
             }
        }
        $this->AjaxReturn($data);
        
    }
        
}	
    
    
    
