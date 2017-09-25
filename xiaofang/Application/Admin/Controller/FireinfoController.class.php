<?php
namespace Admin\Controller;
use Think\Controller;
class FireinfoController extends Controller {
    public function index(){
        // $id=trim(I('id', '', 'htmlspecialchars'));
        // $info=M('Extinguisher');
        // $count=$info->where('pid='.$id)->count();
        // $Page =getpage($count,10);      //引用自定义函数 定义分页样式
        // $show = $Page->show();//  分页显示输出
        // $list=$info->where('pid='.$id)->select();
    
        // $this->assign('fire',$list);
        // $this->assign('page',$show);//  赋值分页输出
        //$this->assign('cate',$cate);
        //var_dump($_POST);
        //echo I('get.id', '', 'htmlspecialchars');
        if (!empty($_GET)) {
            $_SESSION['id']=trim(I('get.id', '', 'htmlspecialchars'));
        }
        
        
        $extinguisher = M('Extinguisher'); // 实例化User对象
        //正常访问,非查询
        //echo $_SESSION['id'];
        //var_dump($_POST);
        switch ($_SESSION['level']) {
            case 1:
                if ($_SESSION['id']==1)
                    $condition=1;   //1代表段领导并且点开了 段领导 菜单
                else
                    $where['group_id']=$_SESSION['id'];
                    $option=11;
                break;
            case 2:
                if ($_SESSION['id']!=1){
                    $where['group_id']=$_SESSION['id'];//2级管理员(材料科)点击非段领导按钮
                    
                }

                if ($_SESSION['id']==4) {
                    $condition=2;  //材料科 点开材料科按钮

                }
                $option=22; //操作权限
                break;

            case 3:
                    if ($_SESSION['istwo']==1) {
                        $option=33;    //具有班组的科室
                        $condition=33;
                    }else{
                        $where['group_id']=$_SESSION['id'];
                        $option=3;
                        $condition=3;
                    }
                    
                
                break;
            case 4:
                    $where['group_id']=$_SESSION['id'];
                    $condition=4;
                    $option=4;
                break;

            
            default:
                # code...
                break;
        }
        if (!empty($_POST)) {
            //echo $_SESSION['id'];
            //var_dump($_POST);// $_POST;
            $where=$_POST;
            //具体部门查询
            if ($_SESSION['id']!=1) {
                $where['group_id']=$_SESSION['id'];
            }
            //对于检测时间 要用模糊查询
            if ($_POST['check_date']) {
                $where['check_date']=array('LIKE',$_POST['check_date'].'%');
            }
            
            //.var_dump($_SESSION);
            //var_dump($where);
            //非全局查询
            
            $where=array_filter($where);  //除去空数组元素
        
            
            //$countPage=10;
            // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
          
            //$where['pid']=$_SESSION['userID'];
            //非超级管理员和超级管理员查询相应的信息
            
            
           //查询功能 
        }
        //要显示的功能字段
        //$this->assign('condition',$condition);
        $this->assign('option',$option);
        $where['change_status']=array('NEQ',2);
        $depart=M('Depart');
        $count=$extinguisher->where($where)->count();
        $Page =getpage($count,10);      //引用自定义函数 定义分页样式
        $show = $Page->show();//  分页显示输出
        $list=$extinguisher->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('die_date')->select();

        
        foreach ($list as $key => $value) {
            $die_date=strtotime($value['die_date']);
            $data[$key]=$value;
            $data1=$depart->field('name')->where(array('id'=>$value['group_id']))->find();
            $data[$key]['group_id']=$data1['name'];
            if ($die_date<time()) {
                
                $data[$key]['status']=0;    //0 表示过期

            }else{
                $differ=($die_date-time())/86400;
                if ($differ<=7) {
                    $data[$key]['status']=1;    //1 表示7天内过期
                }else if ($differ<=30) {
                    $data[$key]['status']=2;    //2 表示30天内过期
                }else{
                    $data[$key]['status']=3;    //3 表示未过期过期
                }
            }
            
        }
        //var_dump($data);
        //var_dump($list);
        //die();
        $this->assign('page',$show);
        $this->assign('wholeFire',$data);// 赋值数据集 

        $this->display();
        
    }
    public function add_extinguisher(){
        $this->display();
    }
    public function doNull($post){
        //数据为空验证
            foreach ($post as $key => $value) {
                if (empty($value)) {
                    $this->error('请填写完整数据！');
                }
            }
    }
    public function doAccept(){
        //id    QS_id depart_id workshop_id  group_id   res_person  responsible_person  variety type pro_date    during_time die_date    place  stock check_date    pid 
        //判断是否填写完整操作    
        $this->doNull(I('post.'));

        //判断QS码是否填写操作
        $m=M('Extinguisher');
        $oldQs=$m->where("QS_id='%s'",I('post.qs'))->select();
        if($oldQs){
            $this->error('已经录入过此灭火器！');
        }else{
            $m->state='1'; 
        }

        
        //过期时间计算
        $now=date('Y-m-d',time());//现在时间
        $start=strtotime(I('post.pro_date'));//生产日期时间戳
        $during=I('post.during_time');//维保时间（年）
        $die=$start+$during*365*24*60*60;//过期时间
        

        //数据库写入操作
        $m->qs_id=I('post.qs');
        $m->depart_id=I('post.depart');
        $m->workshop_id=I('post.workshop');
        $m->group_id=I('post.group');
        $m->res_person=I('post.res');
        $m->variety=I('post.varity');
        $m->type=I('post.type');
        $m->pro_time=I('post.pro_date');
        $m->during_time=I('post.during_time');
        $m->die_date=date('Y-m-d',$die);
        $m->place=I('post.place');
        $m->stock=I('post.stock');
        $m->check_date=date('Y-m-d H:m:s',time());


        if($m->add()){
            $this->success('录入成功！');
        }else{
            $this->error('录入失败！');
        }


    }
    //申请更换时,表里的相应字段记录
    public function apply_change(){
        if (!IS_AJAX) {
            $data['info']='错误操作!';
            $data['status']=0;
        }else{
            $extinguisher = M('Extinguisher'); // 实例化User对象
            $where['qs_id'] = trim(I('get.id', '', 'htmlspecialchars'));
            $data1['change_status']=1;
            $u=$extinguisher->where($where)->save($data1);
                
            if($u){
                    
                    
                    $data['info']='申请提交成功,请等待审核!';
                    $data['status']=1;
                    //$data['url']=U("Fireinfo/index");
                }else{
                    
                    $data['info']='申请提交失败,请勿重复提交申请!';
                    $data['status']=0;
                    //$data['url']=U("index");         //
                }
            //}
            


        }
        //var_dump($data);
        $this->AjaxReturn($data);
    }
    
    

   
}
?>