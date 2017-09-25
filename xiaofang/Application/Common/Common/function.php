<?php 
    // 无限极分类排序函数
    function classify($data,$pid=0){
        static $treeList=array();      //必须加上static关键字 因为后面多次调用自己 否则 $treeList值会变化
        //static $i=0;
        foreach ($data as $key => $value) {
            
            if ($value['pid']==$pid) {
               
                //$value['level']=$i++;
                $treeList[]=$value;
                unset($data[$key]);
                classify($data,$value['id']);      //递归调用自己 所以要给变量$treeList加上static表示静态变量
                // $i=0;
                // var_dump($value);
            }
        }

        return $treeList;
        
    }

    //分页样式的设置
    function getpage($count,$pagesize=10){
        $page = new Think\Page($count,$pagesize);
        $page ->setConfig('header','<li class="p_rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
        $page->lastSuffix = false;//最后一页不显示为总页数

        return $page;
    }
 ?>
