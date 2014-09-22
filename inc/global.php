<?php

ini_set("display_errors","on");
error_reporting(E_ALL ^ E_NOTICE);

header('Content-Type:text/html;charset=utf-8');
!defined("FROOT") && define("FROOT",dirname(dirname(__FILE__)));
include_once(FROOT.'/inc/config.php');//公共配置文件
include_once(FROOT.'/inc/mysqlClass.php');//数据库操作类
include_once(FROOT.'/inc/function.php');//公用函数

