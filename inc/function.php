<?php

/**
 * 防sql注入函数，讲参数中的一些sql关键字替换
 * @param $string
 * @param bool $html
 * @return array|mixed
 */
function stripSql($string,$html=true)
{
	$pattern_arr = array("/ union /i", "/ select /i", "/ update /i", "/ outfile /i", "/ or /i");
	$replace_arr = array('&nbsp;union&nbsp;', '&nbsp;select&nbsp;', '&nbsp;update&nbsp;','&nbsp;outfile&nbsp;', '&nbsp;or&nbsp;');
	if(is_string($string) && $html)//过滤html标签
	{
		$string = strip_tags($string);
	}
	return is_array($string) ? array_map('stripSql', $string,$html) : preg_replace($pattern_arr, $replace_arr, $string);
}

/**
 * 是否是手机,1：手机，0：电脑
 * @param string $uag
 * @return int
 */
function isMobile($uag='')
{
	$uag = $uag?$uag:$_SERVER['HTTP_USER_AGENT'];
	$regex_match="/(nokia|iphone|coolpadwebkit|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|320\*480|480\*|240\*|SHARP|";
	$regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|coolpad|webos|techfaith|palmsource|BBK|LG|NEC|";
	$regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
	$regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
	$regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
	$regex_match.=")/i";
	if(preg_match($regex_match, strtolower($uag)))
	{
		return 1;
	}
	return 0;
}

/**
 * 开启mbstring库
 * @param string $encode
 */
function openMbString($encode='utf-8')
{
	mb_internal_encoding($encode);
}

/**
 * 获取IP函数
 * @return bool
 */
function getIp()
{
	$ip = false;
	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
	{
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($ip)
		{
			array_unshift($ips, $ip);
			$ip = FALSE;
		}
		for ($i = 0; $i < count($ips); $i++)
		{
			if (!preg_match("/^(10|172\.16|192\.168)\./", $ips[$i]))// 判断是否内网的IP
			{
				$ip = $ips[$i];
				break;
			}
		}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/**
 * 中英文名字混合截断到一定长度
 * @param $str
 * @param $mutil
 * @param null $max
 * @return array
 */
function getStrLen($str,$mutil,$max=null)
{
	$now = 0;
	$leng = strlen($str);
	$size = 0;
	$new_str = '';
	$result = array();
	while($now<$leng)
	{
		if(ord($str[$now])>128)
		{
			$new_str .= substr($str,$now,3);
			$now += 3;
			$size += $mutil;
		}
		else
		{
			$new_str .= substr($str,$now,1);
			$now += 1;
			$size++;
		}
		if($size>=$max && $max)
		{
			$result['size'] = $size;
			$result['str'] = $new_str;
			return $result;
		}
	}
	$result['size'] = $size;
	$result['str'] = $new_str;
	return $result;
}

/**
 * 二维数组键值排序
 * @param $arr
 * @param $keys
 * @param string $type
 * @return array
 */
function array_sort($arr,$keys,$type='asc')
{
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v)
	{
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc')
	{
		asort($keysvalue);
	}
	else
	{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v)
	{
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}

/**
 * 多维数组排序
 * @param $multi_array
 * @param $sort_key
 * @param int $sort
 * @return array|int
 */
function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC)
{
	if(is_array($multi_array))
	{
		foreach ($multi_array as $row_array)
		{
			if(is_array($row_array))
			{
				$key_array[] = $row_array[$sort_key];
			}
			else
			{
				return -1;
			}
		}
	}
	else
	{
		return -1;
	}
	array_multisort($key_array,$sort,$multi_array);
	return $multi_array;
}

/**
 * 转换时间显示
 * @param $date
 * @return bool|string
 */
function getDetailTime($date)
{
	$datetime=strtotime($date);
	if(!$date)
	{
		return false;
	}
	$days=(int)((time()-$datetime)/(3600*24));
	if($days==1)
	{
		$newTime="昨天 ".date("H:i",$datetime);
	}
	else if($days==0)
	{
		$hours=(int)((time()-$datetime)/(3600));
		$minutes=(int)((time()-$datetime)/60);
		$seconds=(int)(time()-$datetime);
		$newTime=$hours."小时前";
		if($hours==0)
		{
			$newTime=$minutes."分钟前";
		}
		if($minutes==0)
		{
			if($seconds <= 20)
			{
				$newTime = '刚刚';
			}
			else
			{
				$newTime=$seconds.'秒前';
			}
		}
	}
	else
	{
		$newTime=date("m月d日 H:i",$datetime);
	}
	return $newTime;
}


/**
 * 分页用
 * @param $total
 * @param $page
 * @param $limit
 * @param null $param
 * @return string
 */
function getPage($total,$page,$limit,$param=null)
{
	$total_page = ceil($total/$limit);
	$start_page = $page-2;
	$end_page = $page+2;
	if($start_page<1)
	{
		$start_page = 1;
	}
	if($end_page>$total_page)
	{
		$end_page = $total_page;
	}
	$html = "<div class=\"page\"><span>共有{$total}条记录</span><a href=\"?page=1{$param}\" target=\"_self\">首页</a> ";

	for($pg=$start_page;$pg<=$end_page;$pg++)
	{
		if($page==$pg)
		{
			$html .= "<span class=\"cur\">{$pg}</span> ";
		}
		else
		{
			$html .= "<a href=\"?page={$pg}{$param}\" target=\"_self\">{$pg}</a> ";
		}
	}
	$npage=$page+1;
	$html.="<a href=\"?page={$total_page}{$param}\" target=\"_self\">末页</a>";
	if($npage<=$total_page){
		$html.="<a href=\"?page={$npage}{$param}\" target=\"_self\">下一页</a> ";
	}
	$html.="<span>共{$total_page}页</span>";
	$html.='</div>';
	return $html;
}

