<?php

    	$phone = $_REQUEST['phone'];

	//链接数据库
	mysql_connect('localhost','weixin_cloud','Liying881019') or die('连接数据库失败');
	mysql_query('use HYX_SANFEN_DATA') or die('选择数据库失败');
	
	//判断用户是否存在
	$sql = "select count(id) from ims_bdyx_member where mobile = '$phone' limit 1 ";

	//var_dump(mysql_fetch_row(mysql_query($sql)));exit;
	if(!mysql_fetch_row(mysql_query($sql))){
		echo json_encode('true');
	} else {
		echo json_encode('false');
	}
	exit;
	
?>