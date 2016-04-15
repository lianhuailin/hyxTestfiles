<?php
	
	$mobile = $_REQUEST['mobile'];

	mysql_connect('localhost','weixin_cloud','Liying881019') or die('连接数据库失败');
	mysql_query('use HYX_SANFEN_DATA') or die('选择数据库失败');
	//链接数据库
	
	//判断用户是否存在
	$sql = "select * from ims_bdyx_member where mobile like '%$mobile%'  ";

	//var_dump(mysql_fetch_row(mysql_query($sql)));exit;
	$arr = array();
	$i = 0;
	while ($row = mysql_fetch_row(mysql_query($sql))) {
		$i ++;
		$arr[$i] = $row;
	}
	var_dump($arr);

	exit;

?>