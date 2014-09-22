<?php
include_once("../inc/global.php");

$sub = isset($_POST['sub'])?($_POST['sub']):'';
$name = isset($_POST['name'])?($_POST['name']):'';
$remark = isset($_POST['remark'])?($_POST['remark']):'';
$url = isset($_POST['url'])?($_POST['url']):'';
$putty = isset($_POST['putty'])?($_POST['putty']):'';
$port = isset($_POST['port'])?intval($_POST['port']):0;
$path = isset($_POST['path'])?($_POST['path']):'';
$svn = isset($_POST['svn'])?($_POST['svn']):'';
$db_id = isset($_POST['db_id'])?intval($_POST['db_id']):0;
$id = isset($_POST['id'])?intval($_POST['id']):0;

$ms = new Mysqls;

switch($sub)
{
	case 'add':
		$insert_data = array(
			'name'		=>	$name,
			'remark'	=>	$remark,
			'url'		=>	$url,
			'putty'		=>	$putty,
			'port'		=>	$port,
			'path'		=>	$path,
			'svn'		=>	$svn,
			'db_id'		=>	$db_id
		);
		$ms->insert('pr_project',$insert_data);
		echo "<script>alert('项目[{$name}]添加成功');window.location='../project.php';</script>";
		die();
		break;
	case 'update':
		$condition = " `id`='{$id}' ";
		$update_data = array(
			'name'		=>	$name,
			'remark'	=>	$remark,
			'url'		=>	$url,
			'putty'		=>	$putty,
			'port'		=>	$port,
			'path'		=>	$path,
			'svn'		=>	$svn,
			'db_id'		=>	$db_id
		);
		$ms->update('pr_project',$condition,$update_data);
		echo "<script>alert('项目[{$name}]编辑成功');window.location='../project.php';</script>";
		die();
		break;
	case 'autoComplete':
		$sql = "select * from `pr_project` where `name` like '%{$name}%' limit 20";
		$data = $ms->getRows($sql);
		echo json_encode($data);
		break;
	default:
		break;
}
die();