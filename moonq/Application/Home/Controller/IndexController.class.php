<?php
namespace Home\Controller;
// header("Content-Type:text/html; charset=utf8");//命名空间后面
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	//判断是否设置了分类id  如果没有设置  就全部查出
    	if (!isset($_GET['id'])) {
    		$article=D('article');
    		$count=$article->order('top desc,create_time desc')->relation(true)->field('content,create_ip',true)->count();
    		$Page =getpage($count,5);      //引用自定义函数 定义分页样式
    		$show = $Page->show();//  分页显示输出
    		$list=$article->order('top desc,create_time desc')->relation(true)->field('content,create_ip',true)->limit($Page->firstRow.','.$Page->listRows)->select();
    		
    	}else{
    		//检查是否为恶意提交
    		if (!is_numeric($_GET['id'])) {
    			$this->error();
    		}else{
    			$cid=trim(I('id', '', 'htmlspecialchars'));
                $article=D('article');
                $count=$article->order('top desc,create_time desc')->relation(true)->where('category_id='.$cid)->field('content,create_ip',true)->count();
                $Page =getpage($count,5);      //引用自定义函数 定义分页样式
                $show = $Page->show();//  分页显示输出
    			$list=D('article')->order('top desc,create_time desc')->where('category_id='.$cid)->relation(true)->field('content,create_ip',true)->select();
    		}
    		
    	}
    	
    	//$article=M('article')->order('top desc,create_time desc')->select();
    	//var_dump($article);
    	//exit();
    	$this->assign('article',$list);
    	$this->assign('page',$show);//  赋值分页输出
    	//$this->assign('cate',$cate);
        $this->display();
    }
    public function article_detail(){
    	if (!is_numeric($_GET['id'])) {
    		$this->error();
    	}else{

    		$id=trim(I('id', '', 'htmlspecialchars'));
    		
    		$article=D('article');
    		$a_list=$article->where('id='.$id)->find();

    		$count=$a_list["count"]+1;           //更新点击量
    		$data['id']=$id;
    		$data['count']=$count;
    		$article->save($data);

    		$this->assign('article',$a_list);   //更新点击量
    		$this->display();
    	}
    	
    }
//搜索框功能
    public function search(){
        $article=D('article');
        $content='%'.trim(I('s_content', '', 'htmlspecialchars')).'%';
        //$where['content']=array('like','%$content%');
        $count=$article->order('top desc,create_time desc')->relation(true)->where("content like '%s' or writer like '%s' or title like '%s' or description like '%s' ",array($content,$content,$content,$content))->count();
        $Page =getpage($count,5);      //引用自定义函数 定义分页样式
        $show = $Page->show();//  分页显示输出
        $result=$article->order('top desc,create_time desc')->relation(true)->where("content like '%s' or writer like '%s' or title like '%s' or description like '%s' ",array($content,$content,$content,$content))->field('content,create_ip',true)->limit($Page->firstRow.','.$Page->listRows)->select();
        
        // if (!$result) {
        //     $result[0]['flag']=1;
        // }
        //$this->assign('flag',$null);
        $this->assign('result',$result);
        $this->assign('page',$show);//  赋值分页输出
        $this->display();
        
    }
     
}