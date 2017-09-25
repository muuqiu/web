<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'xxx', // 数据库连接地址
	'DB_NAME'   => 'xxx', // 数据库名
	'DB_USER'   => 'xxx', // 数据库用户名
	'DB_PWD'    => 'xxx', // 数据库密码
	'DB_PORT'   => 3306, // 数据库端口
	'DB_PREFIX' => 'xxx', // 数据库前缀 
	'DB_CHARSET'=> 'utf8', // 数据库编码	    
	//'SHOW_PAGE_TRACE' =>true,    
   //'DB_DEBUG'  =>  TRUE, // 是否开启调试模式
	'URL_MODEL'=>1       // 0 ( 普通模式 ); 1 (PATHINFO  模式 ); 2 (REWRITE 模式 ); 3 ( 兼容模式 ) 默认为 PATHINFO

);
