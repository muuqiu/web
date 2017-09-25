<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends Controller {

    public function index(){
        $this->display();
    }
    public function article_list(){
        $this->display();
    }
    public function article_category(){
        $category=M('article_category')->select();
        $this->assign('cate',$category);
        $this->display();
        
    }
    public function do_addCategory(){
        if (!IS_AJAX) {
            $data=array(
                'info'=>'页面不存在!',
                'status'=>0,
                );
        }else{
            $category=M('article_category');
            $data1=array(
                'title'=>I('title'),         //用I(input)方法接受参数  可以提高安全性
                'sort'=>I('sort'),
                );

            if ($category->add($data1)) {
                $data=array(
                    'url'=> U('article_category'),
                    'info'=>'添加成功!',
                    'status'=>1
                );
            }else{
                $data=array(
                    'info'=>'添加失败!',
                    'status'=>0
                );
            }
        }
        $this->ajaxReturn($data);
    }

    public function add_article(){
        $this->display();
    }
    
}