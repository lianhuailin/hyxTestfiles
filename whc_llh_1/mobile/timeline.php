<?php
	require_once "jssdk/jssdk.php";
	require_once "jssdk/WxPayJsApiPay.php";

	$activityId = /*trim($_GET["a"]);*/1;
	$activity = pdo_fetchall("SELECT * FROM ".tablename('bdyx_activity')." WHERE uniacid = '{$uniacid}' and id='{$activityId}'")[0];

	$uniacid=$_W['uniacid'];
	$myOpenid = "";
	if($_SESSION['myOpenid']) {
		$myOpenid = $_SESSION['myOpenid'];
	} else {
		$jsapi = new JsApiPay();//获取自己的openid
		$myOpenid = $jsapi->GetOpenid(); 
		$_SESSION['myOpenid'] = $myOpenid;
	}

	$jssdk = new JSSDK("wx47e006a40055beaf", "24158783fb92d99cef0994d595680072");
	/*$signPackage = $jssdk->GetSignPackage();*/
	$userinfo = $jssdk->getUserinfo($myOpenid);

	if ($userinfo['subscribe'] == 1) { 
		$shareOpenid = trim($_GET["shareOpenid"]);
		if ($myOpenid == $shareOpenid) {
			header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602"); // shouye
		}
		$transmit = pdo_fetch("SELECT * FROM ".tablename('bdyx_activity_transmit')." WHERE uniacid = '{$uniacid}' and activity_id='{$activityId}' and share_from = 'timeline' and share_openid = '{$shareOpenid}' ");

			//是否点击过 分享链接
		if (empty($transmit)) {
			//获取分享人信息  
			//增加分享人现金
			/*echo "sharemember"; exit;*/
			$share_member =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$shareOpenid}' ");
			
			if (empty($share_member)) { //分享人不存在
				# code...
			} else {  //更新分享人信息
				if ($member['total_money']<=3000) {
					$share_member['current_money'] = $share_member['current_money'] + $activity['transmit_money'];
					$share_member['total_money'] = $share_member['total_money'] + $activity['transmit_money'];
				}

				pdo_update(bdyx_member,$share_member,array('openid' => $shareOpenid));

				//添加分享记录
				$transmit = array('uniacid'=>$_W['uniacid'],

					'activity_id'=>$activity['id'],

					'share_openid'=>$shareOpenid,

					'my_openid'=>$myOpenid,

					'share_money'=>$activity['transmit_money'],

					'share_from'=>'timeline',

					);

				pdo_insert(bdyx_activity_transmit,$transmit);
			}

		} else {
			//你已经帮该好友点击该链接
			echo "你已经帮该好友点击该链接";
		}
		header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602"); // shouye
		/*header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");*/
	} else {
		header("Location: http://www.tpy10.net/ewm.php?name=gh_51ea56e05ff8");
		exit;
	}


  	/*include $this->template('timeline');*/
?>