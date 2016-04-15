<?php

	$pindex = max(1, intval($_GPC['page']));

	$psize = 20;

	

	$parttimes = pdo_fetchall("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and recommends_code !='' ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bdyx_member') . " WHERE uniacid = '{$uniacid}' and recommends_code !='' ");
	

	$pager = pagination($total, $pindex, $psize);

	include $this->template('parttime');

?>