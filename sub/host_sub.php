<?php
include_once("../inc/global.php");

$sub = isset($_POST['sub'])?($_POST['sub']):'';
$name = isset($_POST['name'])?($_POST['name']):'';
$url = isset($_POST['url'])?($_POST['url']):'';
$pwd = isset($_POST['pwd'])?($_POST['pwd']):'';
$id = isset($_POST['id'])?intval($_POST['id']):0;

$ms = new Mysqls;

switch($sub)
{
	case 'add':
		$insert_data = array(
			'name'		=>	$name,
			'url'		=>	$url,
			'pwd'		=>	$pwd
		);
		$ms->insert('db_host',$insert_data);
		echo "<script>alert('数据库host[{$name}]添加成功');window.location='../host.php';</script>";
		die();
		break;
	case 'update':
		$condition = " `id`='{$id}' ";
		$update_data = array(
			'name'		=>	$name,
			'url'		=>	$url,
			'pwd'		=>	$pwd
		);
		$ms->update('db_host',$condition,$update_data);
		echo "<script>alert('数据库host[{$name}]编辑成功');window.location='../host.php';</script>";
		die();
		break;
	default:
		break;
}
die();