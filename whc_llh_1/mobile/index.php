<?php
	require_once "jssdk/jssdk.php";
	require_once "jssdk/WxPayJsApiPay.php";

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
		
		$member =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$myOpenid}' ");
		if (empty($member)) {
			$mem = array('uniacid'=>$_W['uniacid'],

				'openid'=>$myOpenid,

				'nickname'=>$userinfo['nickname'],

				'country'=>$userinfo['country'],

				'province'=>$userinfo['province'],

				'city'=>$userinfo['city'],

				'sex'=>$userinfo['sex'],

				'current_money'=>0,

				'total_money'=>0,

				);
			pdo_insert(bdyx_member,$mem);
		} else {
			if (strlen($member['mobile']) == 0) { 
				$jssdk = new JSSDK("wx47e006a40055beaf", "24158783fb92d99cef0994d595680072");
				$signPackage = $jssdk->GetSignPackage();  //获取签名包

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

				include $this->template('index');exit;
			} else {
				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602"); //shouye
			}
		}
		include $this->template('index');
		/*echo "关注";*/
		/*$my_member = pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$myOpenid}' ");
		$my_member['nickname'] = $userinfo['nickname'];
		$my_member['country'] = $userinfo['country'];
		$my_member['province'] = $userinfo['province'];
		$my_member['city'] = $userinfo['city'];
		$my_member['sex'] = $userinfo['sex'];

		pdo_update(bdyx_member,$my_member,array('openid' => $myOpenid));*/

		/*$shareOpenid = trim($_GET["shareOpenid"]);
		$shareFrom = trim($_GET["shareFrom"]);
		$money = 10;*/

		

		//参加活动      
		
		

		/*$shareOpenid = trim($_GET["shareOpenid"]);
		$shareFrom = trim($_GET["shareFrom"]);
		echo $this->createMobileUrl('index')."&shareOpenid=".$myOpenid."&shareFrom=".$shareFrom; exit;*/
		
		/*$my_member = pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$myOpenid}' ");
		
		if (empty($my_member)) {
			include $this->template('index');
		} else {
			if ($my_member['mobile'] == "") {
			 	include $this->template('index');
			 } else {
			 	Header("Location: $url_money"); 
			 }
			
		}*/
		
		
	  	
		/*Header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602"); // 跳到我的汇币界面*/

	} else {
		/*echo "未关注";*/
		header("Location: http://www.tpy10.net/ewm.php?name=gh_51ea56e05ff8");
		exit;
	}     

?>