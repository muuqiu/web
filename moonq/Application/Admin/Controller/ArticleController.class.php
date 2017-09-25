<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends BaseController {

    public function index(){
        $this->display();
    }
    public function article_list(){
        $article=M('article')->order('top desc,last_time desc')->select();
        $cate=M('article_category')->select();
        $this->assign('article',$article);
        $this->assign('cate',$cate);
        $this->display();
    }
    public function article_category(){
        $category=M('article_category')->select();
        $this->assign('cate',$category);
        $this->display();
        
    }
    public function edit_cate(){
        
        if (!IS_AJAX) {
            $data=array(
                'info'=>'页面不存在!',
                'status'=>0,
                );
        }else{
            $id=$_GET['id'];


            if ($category=M('article_category')->where('id='.$id)->find()) {
                $data=$category;
                $data['status']=1;
            }else{
                $data['status']=0;
            }
            
        
        }
        $this->ajaxReturn($data);    //调用ajaxReturn方法 默认返回Jason数据

    }
    public function do_addCategory(){
        $flag=I('flag');

        if (!IS_AJAX) {
            $data=array(
                'info'=>'页面不存在!',
                'status'=>0,
                );
        }else{
            $category=M('article_category');
            if ($flag==0) {
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
            }else if ($flag==1) {
                $data1=array(
                    'title'=>I('title'),         //用I(input)方法接受参数  可以提高安全性
                    'sort'=>I('sort'),
                    'id'=>I('id'),
                    );

                if ($category->save($data1)) {
                    $data=array(
                        'url'=> U('article_category'),
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
        $this->ajaxReturn($data);
    }

    public function add_article(){
        $category=M('article_category')->order('id')->select();
        $this->assign('list',$category);
        $this->display();
    }
    public function do_addArticle(){
        if (!IS_AJAX) {
            $data=array(
                'info'=>'页面不存在!',
                'status'=>0,
                );
        }else{
            $article=M('article');
            $create_time          = strtotime(date("Y-m-d H:i:s",time()));
            $last_time            = strtotime(date("Y-m-d H:i:s",time()));
            $data1['title']      = trim(I('post.title', '', 'htmlspecialchars'));        
            $data1['category_id']      = trim(I('post.category_id', '', 'htmlspecialchars'));
            $data1['photo']        = trim(I('post.photo', '', 'htmlspecialchars'));
            $data1['keywords']    = trim(I('post.keywords', '', 'htmlspecialchars'));
            $data1['description']     = trim(I('post.description', '', 'htmlspecialchars'));
            $data1['content']    = $_POST['content'];          
            $data1['count']        = trim(I('post.count', '', 'htmlspecialchars'));
            $data1['type']       = trim(I('post.type', '', 'htmlspecialchars'));
            $data1['top']      = trim(I('post.top', '', 'htmlspecialchars'));
            $data1['writer']     = trim(I('post.writer', '', 'htmlspecialchars'));
            $data1['create_time']  = $create_time;
            $data1['create_ip']    = get_client_ip();
            $data1['last_time']    = $last_time;
            //$data1['os']       = getOS();
            //var_dump($data1);
            //exit();
            if ($article->add($data1)) {
                $data=array(
                    'url'=> U('article_list'),
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

     /*
      *删除文章
      */   
    public function del_article() {

        
        M('article')->where(array('id' => I('id')))->delete();
        $this->redirect('article_list');

    }
    /*
      *删除分类
      */   
    public function del_cate() {

        
        M('article_category')->where(array('id' => I('id')))->delete();
        $this->redirect('article_category');

    }

    /*
      * 编辑文章
      */   
    public function edit() {

        $list = M('article_category')->select();
        $detail = M('article')->where(array('id' => I('id')))->find(); //这样子查询出来的结果为一维数组
        //$detail['content']=htmlspecialchars_decode($detail['content']);
        // var_dump($detail);
        // exit;
        $this->assign('detail', $detail);
        $this->assign('list', $list);
        $this->display();

    }

    public function do_edit() {

        $id = I('id', '', htmlspecialchars);
        
        if (!IS_AJAX) {
            $data['info']='页面不存在!';
            $data['status']='0';
        } else {
            $article=M('Article');
            $last_time         = strtotime(date("Y-m-d H:i:s",time()));
            $data1['title']      = trim(I('post.title', '', 'htmlspecialchars'));        
            $data1['category_id']      = trim(I('post.category_id', '', 'htmlspecialchars'));
            $data1['photo']        = trim(I('post.photo', '', 'htmlspecialchars'));
            $data1['keywords']    = trim(I('post.keywords', '', 'htmlspecialchars'));
            $data1['description']     = trim(I('post.description', '', 'htmlspecialchars'));
            $data1['content']    = $_POST['content'];          
            $data1['count']        = trim(I('post.count', '', 'htmlspecialchars'));
            $data1['type']       = trim(I('post.type', '', 'htmlspecialchars'));
            $data1['top']      = trim(I('post.top', '', 'htmlspecialchars'));
            $data1['writer']     = trim(I('post.writer', '', 'htmlspecialchars'));
            
            $data1['create_ip']    = get_client_ip();
            $data1['last_time']    = $last_time; 

            if ($article->where('id='.$id)->save($data1)) {
                $data=array(
                    'url'=> U('article_list'),
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
        $this->ajaxReturn($data);
       
        
    }
}