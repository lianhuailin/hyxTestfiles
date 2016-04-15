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
		$member =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$myOpenid}' ");
		
		if (empty($member)) {
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
		} else {
			
		}
		
		
	} else {
		header("Location: http://www.tpy10.net/ewm.php?name=gh_51ea56e05ff8");
		exit;
	}

	/*require_once "jssdk/jssdk.php";

	$uniacid=$_W['uniacid'];
	$jssdk = new JSSDK("wx47e006a40055beaf", "24158783fb92d99cef0994d595680072");
		$signPackage = $jssdk->GetSignPackage();*/
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
	
  	include $this->template('recommend_code');

?>