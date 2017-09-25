<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 数据库连接地址
	'DB_NAME'   => 'xxx', // 数据库名
	'DB_USER'   => 'xxx', // 数据库用户名
	'DB_PWD'    => 'xxx', // 数据库密码
	'DB_PORT'   => 3306, // 数据库端口
	'DB_PREFIX' => 'xxx', // 数据库前缀 
	'DB_CHARSET'=> 'utf8', // 数据库编码	    
	'URL_CASE_INSENSITIVE'=>true,
	//'SHOW_PAGE_TRACE' =>true,    
   
	'URL_MODEL'=>1,       // 0 ( 普通模式 ); 1 (PATHINFO  模式 ); 2 (REWRITE 模式 ); 3 ( 兼容模式 ) 默认为 PATHINFO

	   /* 错误页面模板 */
	// 'ERROR_PAGE'            =>  APP_PATH.'Public/my404.html', 
     'TMPL_ACTION_ERROR'     =>MODULE_PATH.'View/Public/my404.html',  // 错误定向页面
 //    'TMPL_ACTION_SUCCESS'   =>  MODULE_PATH.'View/Public/success.html', // 默认成功跳转对应的模板文件
    //'TMPL_EXCEPTION_FILE'   =>MODULE_PATH.'View/Public/my404.html',// 异常页面的模板文件


);
