<?php

	$attentionRate = pdo_fetchall("SELECT distinct country,province,city FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' ORDER BY id DESC");

	for ($i=0; $i < count($attentionRate); $i++) { 
		$country = $attentionRate[$i]['country'];
		$province = $attentionRate[$i]['province'];
		$city = $attentionRate[$i]['city'];
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bdyx_member') . " WHERE uniacid = '{$uniacid}' and country = '{$country}' and province = '{$province}' and city = '{$city}' ");

		$attentionRate[$i]['count'] = $total;
	}

	usort($attentionRate);

	include $this->template('attention');

?>