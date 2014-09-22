<?php
$header = 'host';
include_once("inc/global.php");

$page = isset($_GET['page'])?intval($_GET['page']):1;
$limit = 15;
$start = ($page-1)*$limit;

$ms = new Mysqls;

$sql = "select * from `db_host` order by `id` desc limit {$start},{$limit}";
$data = $ms->getRows($sql);

$sql = "select count(*) from `db_host` limit 1";
$total = $ms->getOne($sql);
$pager = getPage($total,$page,$limit);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Host</title>
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
		<form action="sub/host_sub.php" method="post">
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
					<td>网址：</td>
					<td><input type="text" id="url" name="url" size="50"/></td>
					<td></td>
				</tr>
				<tr>
					<td>用户名：</td>
					<td><input type="text" id="name" name="name" value="ci123dba"/></td>
					<td></td>
				</tr>
				<tr>
					<td>密码：</td>
					<td><input type="text" id="pwd" name="pwd" value="hanfuboyuan0619"/></td>
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
				<td width="250px">网址</td>
				<td width="100px">登录名</td>
				<td width="120px">密码</td>
				<td>操作</td>
			</tr>
			</thead>
			<tbody>
			<?php foreach($data as $k=>$v){ ?>
				<tr>
					<td><?php echo $v['id'];?></td>
					<td>
						<a href="<?php echo $v['url'];?>" target="_blank"><?php echo $v['url'];?></a>
					</td>
					<td><?php echo $v['name'];?></td>
					<td><?php echo $v['pwd'];?></td>
					<td>
						<a href="javascript:void(0);" onclick="showEdit('update_<?php echo $v['id'];?>')">编辑</a>
					</td>
				</tr>
				<tr>
					<td colspan="5">
						<!--update div-->
						<div class="mcontent" id="update_<?php echo $v['id'];?>" style="display: none;">
							<form action="sub/host_sub.php" method="post">
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
										<td>网址：</td>
										<td><input type="text" id="url" name="url" size="50" value="<?php echo $v['url'];?>"/></td>
										<td></td>
									</tr>
									<tr>
										<td>用户名：</td>
										<td><input type="text" id="name" name="name" value="<?php echo $v['name'];?>"/></td>
										<td></td>
									</tr>
									<tr>
										<td>密码：</td>
										<td><input type="text" id="pwd" name="pwd" value="<?php echo $v['pwd'];?>"/></td>
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
				<td colspan="5"><?php echo $pager;?></td>
			</tr>
			</tfoot>
		</table>
	</div>
</div>
</body>
</html>