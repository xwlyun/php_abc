<?php
include_once("../inc/global.php");

$sub = isset($_POST['sub'])?($_POST['sub']):'';
$name = isset($_POST['name'])?($_POST['name']):'';
$host_id = isset($_POST['host_id'])?intval($_POST['host_id']):0;
$id = isset($_POST['id'])?intval($_POST['id']):0;

$ms = new Mysqls;

switch($sub)
{
	case 'add':
		$insert_data = array(
			'name'		=>	$name,
			'host_id'	=>	$host_id
		);
		$ms->insert('db_database',$insert_data);
		echo "<script>alert('数据库[{$name}]添加成功');window.location='../database.php';</script>";
		die();
		break;
	case 'update':
		$condition = " `id`='{$id}' ";
		$update_data = array(
			'name'		=>	$name,
			'host_id'	=>	$host_id
		);
		$ms->update('db_database',$condition,$update_data);
		echo "<script>alert('数据库[{$name}]编辑成功');window.location='../database.php';</script>";
		die();
		break;
	default:
		break;
}
die();