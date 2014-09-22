<?php
$header = 'database';
include_once("inc/global.php");

$page = isset($_GET['page'])?intval($_GET['page']):1;
$limit = 15;
$start = ($page-1)*$limit;

$ms = new Mysqls;

$sql = "select * from `db_database` order by `id` desc limit {$start},{$limit}";
$data = $ms->getRows($sql);
foreach($data as $k=>$v)
{
	$host_id = $v['host_id'];
	$sql = "select * from `db_host` where `id`='{$host_id}' limit 1";
	$host_info = $ms->getRow($sql);
	$data[$k]['host'] = $host_info;
}

$sql = "select count(*) from `db_database` limit 1";
$total = $ms->getOne($sql);
$pager = getPage($total,$page,$limit);

$sql = "select * from `db_host`";
$arr_host = $ms->getRows($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Database</title>
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
	</div>
	<!--add div-->
	<div class="mcontent" id="add" style="display: none;">
		<form action="sub/database_sub.php" method="post">
			<table class="tablist" width="100%" border="0" cellspacing="0" cellpadding="0">
				<thead>
				<tr>
					<td width="100px;">新增数据库host</td>
					<td width="200px;"></td>
					<td></td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>名称：</td>
					<td><input type="text" id="name" name="name"/></td>
					<td></td>
				</tr>
				<tr>
					<td>host：</td>
					<td>
						<select id="host_id" name="host_id">
							<option value="0">----</option>
							<?php foreach($arr_host as $k=>$v){ ?>
								<option value="<?php echo $v['id'];?>"><?php echo $v['url'];?></option>
							<?php } ?>
						</select>
					</td>
					<td></td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
					<td colspan="3">
						<input type="submit" value="添加"/>
						<input type="button" value="取消" onclick="showEdit('add')"/>
						<input type="hidden" id="sub" name="sub" value="add"/>
					</td>
				</tr>
				</tfoot>
			</table>
		</form>
	</div>
	<!--end add div-->
	<div class="mcontent" >
			<table class="tablist" width="100%" border="0" cellspacing="0" cellpadding="0">
				<thead>
				<tr>
					<td width="50px">序号</td>
					<td width="100px">名称</td>
					<td width="270px">网址</td>
					<td>操作</td>
				</tr>
				</thead>
				<tbody>
				<?php foreach($data as $k=>$v){ ?>
					<tr>
						<td><?php echo $v['id'];?></td>
						<td>
							<a href="<?php echo $v['host']['url'];?>" target="_blank"><?php echo $v['name'];?></a>
						</td>
						<td>
							<a href="<?php echo $v['host']['url'];?>" target="_blank"><?php echo $v['host']['url'];?></a>
						</td>
						<td>
							<a href="javascript:void(0);" onclick="showEdit('update_<?php echo $v['id'];?>')">编辑</a>
							&nbsp;|&nbsp;
							<a href="table.php?db_id=<?php echo $v['id'];?>" target="_blank">查看</a>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<!--update div-->
							<div class="mcontent" id="update_<?php echo $v['id'];?>" style="display: none;">
								<form action="sub/database_sub.php" method="post">
									<table class="tablist" width="100%" border="0" cellspacing="0" cellpadding="0">
										<thead>
										<tr>
											<td width="100px">编辑</td>
											<td width="200px"></td>
											<td></td>
										</tr>
										</thead>
										<tbody>
										<tr>
											<td>名称：</td>
											<td><input type="text" id="name" name="name" value="<?php echo $v['name'];?>"/></td>
											<td></td>
										</tr>
										<tr>
											<td>host：</td>
											<td>
												<select id="host_id" name="host_id">
													<option value="0" <?php if($v['host_id']==0){echo 'selected';}?>>----</option>
													<?php foreach($arr_host as $r){ ?>
														<option value="<?php echo $r['id'];?>" <?php if($v['host_id']==$r['id']){echo 'selected';}?>><?php echo $r['url'];?></option>
													<?php } ?>
												</select>
											</td>
											<td></td>
										</tr>
										</tbody>
										<tfoot>
										<tr>
											<td colspan="3">
												<input type="submit" value="确定"/>
												<input type="button" value="取消" onclick="showEdit('update_<?php echo $v['id'];?>')"/>
												<input type="hidden" id="sub" name="sub" value="update"/>
												<input type="hidden" id="id" name="id" value="<?php echo $v['id'];?>"/>
											</td>
										</tr>
										</tfoot>
									</table>
								</form>
							</div>
							<!--end update div-->
						</td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
				<tr>
					<td colspan="4"><?php echo $pager;?></td>
				</tr>
				</tfoot>
			</table>
	</div>
</div>
</body>
</html>