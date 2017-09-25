<?php 
namespace Home\Model;
use Think\Model;
use Think\Model\RelationModel;
	class ArticleModel extends RelationModel{
		protected $_link = array(
		'article_category'=>array(
			'mapping_type' => self::BELONGS_TO,

			'foreign_key' => 'category_id',
			//'mapping_name' => 'category_name',
			'mapping_fields'=> 'title',      //查询的字段
			'as_fields'=>'title:category_name',     //查询的字段作为能一个字段
			),
		);
	}
 ?>