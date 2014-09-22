<?php
include_once("../inc/global.php");

$sub = isset($_POST['sub'])?($_POST['sub']):'';
$db_id = isset($_POST['db_id'])?intval($_POST['db_id']):0;
$name = isset($_POST['name'])?($_POST['name']):'';
$remark = isset($_POST['remark'])?($_POST['remark']):'';

$ms = new Mysqls;

switch($sub)
{
	case 'add':
		$sql = "select * from `db_table` where `name`='{$name}' and `db_id`='{$db_id}' limit 1";
		$data = $ms->getRow($sql);
		if($data)
		{
			echo "<script>alert('表[{$name}]已存在');window.location='../table.php';</script>";
			die();
		}
		$insert_data = array(
			'name'		=>	$name,
			'remark'	=>	$remark,
			'db_id'		=>	$db_id
		);
		$ms->insert('db_table',$insert_data);
		echo "<script>alert('表[{$name}]添加成功');window.location='../table.php';</script>";
		die();
		break;
	default:
		break;
}
die();