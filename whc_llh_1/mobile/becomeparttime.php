<?php
	require_once "jssdk/jssdk.php";
	require_once "jssdk/WxPayJsApiPay.php";
	
	$uniacid=$_W['uniacid'];

	if($_SESSION['myOpenid']) {
		$myOpenid = $_SESSION['myOpenid'];
	} else {
		$jsapi = new JsApiPay();//获取自己的openid
		$myOpenid = $jsapi->GetOpenid(); 
		$_SESSION['myOpenid'] = $myOpenid;
	}

	$jssdk = new JSSDK("wx47e006a40055beaf", "24158783fb92d99cef0994d595680072");	
	$userinfo = $jssdk->getUserinfo($myOpenid);

	if ($userinfo['subscribe'] == 1) {
		//查看该用户是否是兼职人员
		$parttime_member =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$myOpenid}' ");
		if (!empty($parttime_member)) {
			/*echo "在数据中";*/
			
		} else {
			$jssdk = new JSSDK("wx47e006a40055beaf", "24158783fb92d99cef0994d595680072");
			
			$userinfo = $jssdk->getUserinfo($myOpenid);
			 $mem_new = array('uniacid'=>$_W['uniacid'],

			          'openid'=>$myOpenid,

			          'nickname'=>$userinfo['nickname'],

			          'country'=>$userinfo['country'],

			          'province'=>$userinfo['province'],

			          'city'=>$userinfo['city'],

			          'sex'=>$userinfo['sex'],

			          'current_money'=>0,

			          'total_money'=>0,

			          );

			pdo_insert(bdyx_member,$mem_new);
			/*echo "不在数据中";*/
		}

		$recommends_code = $parttime_member['recommends_code'];
		if (!empty($recommends_code)) {
			# code...
			/*echo "兼职人员";*/
		} else {
			/*echo "不是兼职人员";*/
			$recommends_code = mt_rand(1000, 9999);
			/*echo '推荐码：'."$recommends_code";*/
			//推荐码是否被
			$member =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and recommends_code='{$recommends_code}' ");
			if (empty($member)) {
				/*echo "未被使用";*/

				$parttime_member['recommends_code'] = $recommends_code;
				if (pdo_update(bdyx_member,$parttime_member,array('openid' => $myOpenid))) {
				 	# code...
				 	/*echo '以为该兼职生成推荐码：'."$recommends_code";*/
				 } else {
				 	/*echo "生成推荐码失败";*/
				 }
				 
			} else {
				/*echo "已被使用";*/
			}
			  
		}
	} else {
		header("Location: http://www.tpy10.net/ewm.php?name=gh_51ea56e05ff8");
		exit;
	}
	
	
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

	/*echo $myOpenid; exit;*/
	//时间
	include $this->template('custom');
?>