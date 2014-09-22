<?php
$header = 'project';
include_once("inc/global.php");

$page = isset($_GET['page'])?intval($_GET['page']):1;
$limit = 10;
$start = ($page-1)*$limit;

$ms = new Mysqls;

$sql = "select * from `pr_project` order by `id` desc limit {$start},{$limit}";
$data = $ms->getRows($sql);

foreach($data as $k=>$v)
{
	$db_id = $v['db_id'];
	$sql = "select * from `db_database` where `id`='{$db_id}' limit 1";
	$db_info = $ms->getRow($sql);
	$host_id = $db_info['host_id'];
	$sql = "select * from `db_host` where `id`='{$host_id}' limit 1";
	$host_info = $ms->getRow($sql);
	$db_info['host'] = $host_info;
	$data[$k]['db'] = $db_info;
}

$sql = "select count(*) from `pr_project` limit 1";
$total = $ms->getOne($sql);
$pager = getPage($total,$page,$limit);

$sql = "select * from `db_database`";
$arr_database = $ms->getRows($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Project</title>
<?php include_once('./global/meta.php');?>
<!--<link href="style/autoComplete/autoComplete.css" rel="stylesheet" type="text/css"/>-->
<!--<script src="style/autoComplete/autoComplete.js" language="javascript"></script>-->
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
<!--		&nbsp;搜索：-->
<!--		<input type="text" id="search" onkeyup="autoComplete.start(event)"/>-->
<!--		<div class="auto_hidden" id="auto"></div>-->
<!--		<script>-->
<!--			var autoComplete=new AutoComplete('search','auto',['b0','b12','b22','b3','b4','b5','b6','b7','b8','b2','abd','ab','acd','accd','b1','cd','ccd','cbcv','cxf']);-->
<!--		</script>-->
	</div>
	<!--add div-->
	<div class="mcontent" id="add" style="display: none;">
		<form action="sub/project_sub.php" method="post">
			<table class="tablist" width="100%" border="0" cellspacing="0" cellpadding="0">
				<thead>
				<tr>
					<td width="100px;">新增项目</td>
					<td width="200px;"></td>
					<td></td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>名称：</td>
					<td><input type="text" id="name" name="name" size="50"/></td>
					<td></td>
				</tr>
				<tr>
					<td>备注：</td>
					<td><input type="text" id="remark" name="remark" size="50"/></td>
					<td></td>
				</tr>
				<tr>
					<td>网址：</td>
					<td><input type="text" id="url" name="url" size="50"/></td>
					<td></td>
				</tr>
				<tr>
					<td>putty：</td>
					<td><input type="text" id="putty" name="putty"/></td>
					<td></td>
				</tr>
				<tr>
					<td>端口：</td>
					<td><input type="text" id="port" name="port"/></td>
					<td></td>
				</tr>
				<tr>
					<td>路径：</td>
					<td><input type="text" id="path" name="path" size="50"/></td>
					<td></td>
				</tr>
				<tr>
					<td>svn：</td>
					<td><input type="text" id="svn" name="svn" size="50"/></td>
					<td></td>
				</tr>
				<tr>
					<td>数据库：</td>
					<td>
						<select id="db_id" name="db_id">
							<option value="0">----</option>
							<?php foreach($arr_database as $k=>$v){ ?>
								<option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
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
	<div class="mcontent">
		<table class="tablist" width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<td width="50px">序号</td>
				<td width="100px">名称</td>
				<td width="200px">备注</td>
				<td width="200px">网址</td>
				<td width="200px">putty</td>
				<td width="200px">svn</td>
				<td width="100px">数据库</td>
				<td>操作</td>
			</tr>
			</thead>
			<tbody>
			<?php foreach($data as $k=>$v){ ?>
			<tr>
				<td><?php echo $v['id'];?></td>
				<td>
					<a href="<?php echo $v['url'];?>" target="_blank"><?php echo $v['name'];?></a>
				</td>
				<td><?php echo $v['remark'];?></td>
				<td>
					<a href="<?php echo $v['url'];?>" target="_blank"><?php echo $v['url'];?></a>
				</td>
				<td><?php echo $v['putty'].':'.$v['port'].'<br/>'.$v['path'];?></td>
				<td><?php echo $v['svn'];?></td>
				<td>
					<a href="<?php echo $v['db']['host']['url'];?>" target="_blank"><?php echo $v['db']['name'];?></a>
				</td>
				<td>
					<a href="javascript:void(0);" onclick="showEdit('update_<?php echo $v['id'];?>')">编辑</a>
				</td>
			</tr>
			<tr>
				<td colspan="8">
					<!--update div-->
					<div class="mcontent" id="update_<?php echo $v['id'];?>" style="display: none;">
						<form action="sub/project_sub.php" method="post">
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
									<td><input type="text" id="name" name="name" size="50" value="<?php echo $v['name'];?>"/></td>
									<td></td>
								</tr>
								<tr>
									<td>备注：</td>
									<td><input type="text" id="remark" name="remark" size="50" value="<?php echo $v['remark'];?>"/></td>
									<td></td>
								</tr>
								<tr>
									<td>网址：</td>
									<td><input type="text" id="url" name="url" size="50" value="<?php echo $v['url'];?>"/></td>
									<td></td>
								</tr>
								<tr>
									<td>putty：</td>
									<td><input type="text" id="putty" name="putty" value="<?php echo $v['putty'];?>"/></td>
									<td></td>
								</tr>
								<tr>
									<td>端口：</td>
									<td><input type="text" id="port" name="port" value="<?php echo $v['port'];?>"/></td>
									<td></td>
								</tr>
								<tr>
									<td>路径：</td>
									<td><input type="text" id="path" name="path" size="50" value="<?php echo $v['path'];?>"/></td>
									<td></td>
								</tr>
								<tr>
									<td>svn：</td>
									<td><input type="text" id="svn" name="svn" size="50" value="<?php echo $v['svn'];?>"/></td>
									<td></td>
								</tr>
								<tr>
									<td>数据库：</td>
									<td>
										<select id="db_id" name="db_id">
											<option value="0" <?php if($v['db_id']==0){echo 'selected';};?>>----</option>
											<?php foreach($arr_database as $r){ ?>
												<option value="<?php echo $r['id'];?>" <?php if($r['id']==$v['db_id']){echo 'selected';}?>><?php echo $r['name'];?></option>
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
				<td colspan="8"><?php echo $pager;?></td>
			</tr>
			</tfoot>
		</table>
	</div>
</div>
</body>
</html>