<?php
	require_once "jssdk/jssdk.php";
	require_once "jssdk/WxPayJsApiPay.php";
	
	$advices = $_REQUEST['advices'];
	$phone = $_REQUEST['phone'];
	$name = $_REQUEST['name'];


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
	$money = 10;
	$userinfo = $jssdk->getUserinfo($myOpenid);
	if ($userinfo['subscribe'] == 1) {
		$member =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$myOpenid}' ");
		
		if (empty($member)) {
			/*echo "未关注";*/
			$mem = array('uniacid'=>$_W['uniacid'],

				'openid'=>$myOpenid,

				'nickname'=>$userinfo['nickname'],

				'country'=>$userinfo['country'],

				'province'=>$userinfo['province'],

				'city'=>$userinfo['city'],

				'sex'=>$userinfo['sex'],

				'mobile'=>$phone,

				'name'=>$name,

				'advices'=>$advices,

				'current_money'=>$money,

				'total_money'=>$money,

				);
			if(pdo_insert(bdyx_member,$mem)) {
				/*echo "成功添加";*/
				$transmit = array('uniacid'=>$_W['uniacid'],

					'activity_id'=>4,

					'my_openid'=>$myOpenid,

					'share_money'=>$money,

					'share_from'=>'system',

					);

				pdo_insert(bdyx_activity_transmit,$transmit);
				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
				exit;
			} else {
				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
				exit;
			}


		} else {
			if (strlen($member['mobile']) == 0) {
				/*var_dump($member['mobile']);exit;*/
				# code...Header("Location: $url_money"); 
				$member['mobile'] = $phone;
				$member['name'] = $name;
				$member['advices'] = $advices;
				$member['current_money'] = $member['current_money'] + 10;

				$member['total_money'] = $member['total_money'] + 10;
				/*echo '<pre/>';
				var_dump($member);exit;*/
				pdo_update(bdyx_member,$member,array('openid' => $myOpenid));
				$transmit = array('uniacid'=>$_W['uniacid'],

					'activity_id'=>4,

					'my_openid'=>$myOpenid,

					'share_money'=>$money,

					'share_from'=>'system',

					);

				pdo_insert(bdyx_activity_transmit,$transmit);
			} else {
				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
			}
			header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
			exit;
		}
	} else {
		header("Location: http://www.tpy10.net/ewm.php?name=gh_51ea56e05ff8");
		exit;
	}
	

	//分享信息
	//分享给好友
	$shareAppMessage = array('title' => '好友-分享得现金（万汇城）',

			'desc'=>'分享给好友，你获得10元现金礼包，好友帮你分享，你再得5元--万汇城',

			//'link'=>$this->createMobileUrl('index')."&shareOpenid=".$myOpenid."&shareFrom=appMessage",
			'link'=>'http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=601&shareOpenid='.$myOpenid.'&shareFrom=appMessage',

			'imgUrl'=>'http://020.liyingkeji.com/test/img/share.jpg'

		 	);

	//分享到朋友圈
	$ShareTimeline = array('title' => '朋友圈-分享得现金（万汇城）',

			'link'=>"http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=601&shareOpenid=".$myOpenid."&shareFrom=timeline",

			'imgUrl'=>'http://020.liyingkeji.com/test/img/share.jpg'

		 	);

	include $this->template('phone');
?>