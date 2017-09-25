<?php 
namespace Admin\Model;
use Think\Model;
use Think\Model\RelationModel;
class Depart1Model extends RelationModel{
	protected $_link = array(
	'depart'=>array(
		'mapping_type' =>self::BELONGS_TO,

		'foreign_key' => 'pid',
		//'mapping_name' => 'category_name',
		'mapping_fields'=> 'name',      //查询的字段
		'as_fields'=>'name:depart_name',     //查询的字段作为能一个字段
		),
	);
}
 ?>
