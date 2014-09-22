<?php
$header = 'table';
include_once("inc/global.php");

$page = isset($_GET['page'])?intval($_GET['page']):1;
$limit = 15;
$start = ($page-1)*$limit;
$where = " `id`>0 ";
$db_id = isset($_GET['db_id'])?intval($_GET['db_id']):0;
if($db_id>0)
{
	$where .= " and `db_id`='{$db_id}' ";
	$param .= "&db_id={$db_id}";
}

$ms = new Mysqls;

$sql = "select * from `db_table` where {$where} order by `id` desc limit {$start},{$limit}";
$data = $ms->getRows($sql);

foreach($data as $k=>$v)
{
	$_db_id = $v['db_id'];
	$sql = "select * from `db_database` where `id`='{$_db_id}' limit 1";
	$db_info = $ms->getRow($sql);
	$host_id = $db_info['host_id'];
	$sql = "select * from `db_host` where `id`='{$host_id}' limit 1";
	$host_info = $ms->getRow($sql);
	$db_info['host'] = $host_info;
	$data[$k]['db'] = $db_info;

}

$sql = "select count(*) from `db_table` where {$where} limit 1";
$total = $ms->getOne($sql);
$pager = getPage($total,$page,$limit,$param);

$sql = "select * from `db_database`";
$arr_database = $ms->getRows($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Table</title>
<?php include_once('./global/meta.php');?>
<script type="text/javascript">
function showEdit(id)
{
	var div = document.getElementById(id);
	if(div.style.display == 'none')
	{
		div.style.display = '';
	}
	else
	{
		div.style.display = 'none';
	}
}
</script>
</head>
<body>
<div class="main">
	<?php include_once('./global/nav.php');?>
	<div class="position">
		<a href="javascript:void(0);" onclick="showEdit('add')">新增</a>
		&nbsp;所属：
		<select onchange="location.href='?db_id='+this.value;">
			<option value="0" <?php if($db_id==0){echo 'selected';}?>>----</option>
			<?php foreach($arr_database as $k=>$v){ ?>
				<option value="<?php echo $v['id'];?>" <?php if($db_id==$v['id']){echo 'selected';}?>><?php echo $v['name'];?></option>
			<?php } ?>
		</select>
	</div>
	<!--add div-->
	<div class="mcontent" id="add" style="display: none;">
		<form action="sub/table_sub.php" method="post">
		<table class="tablist" width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<td width="100px;">新增表</td>
				<td width="200px;"></td>
				<td></td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>所属：</td>
				<td>
					<select id="db_id" name="db_id">
						<?php foreach($arr_database as $k=>$v){ ?>
							<option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
						<?php } ?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>表名：</td>
				<td><input type="text" id="name" name="name"/></td>
				<td></td>
			</tr>
			<tr>
				<td>备注：</td>
				<td><textarea rows="5" cols="60" id="remark" name="remark"></textarea></td>
				<td></td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="3">
					<input type="submit" value="添加"/>
					<input type="button" value="取消"/>
					<input type="hidden" id="sub" name="sub" value="add"/>
				</td>
			</tr>
			</tfoot>
		</table>
		</form>
	</div>
	<!--end add div-->
	<div class="mcontent">
		<table class="tablist" width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<td width="50px">序号</td>
				<td width="150px">所属db</td>
				<td width="150px">名称</td>
				<td width="400px">备注</td>
				<td></td>
			</tr>
			</thead>
			<tbody>
			<?php foreach($data as $k=>$v){ ?>
				<tr>
					<td><?php echo $v['id'];?></td>
					<td><?php echo $v['db']['name'];?></td>
					<td><?php echo $v['name'];?></td>
					<td><?php echo $v['remark'];?></td>
					<td></td>
				</tr>
			<?php } ?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="5"><?php echo $pager;?></td>
			</tr>
			</tfoot>
		</table>
	</div>
</div>
</body>
</html>