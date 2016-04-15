<?php
	require_once "jssdk/jssdk.php";
	require_once "jssdk/WxPayJsApiPay.php";
	
	$activityId = /*trim($_GET["a"]);*/2;
	$activity = pdo_fetch("SELECT * FROM ".tablename('bdyx_activity')." WHERE uniacid = '{$uniacid}' and id='{$activityId}'");

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
		$shareFrom =  /*empty($_GET["shareFrom"]) ? "appMessage" : trim($_GET['share_from']);*/"appMessage";
		if (($myOpenid == $shareOpenid) || ($shareOpenid == null)) {
			/*var_dump("1");*/
			header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
		}
		/*var_dump(2);exit;*/
		//判断我是否在member表中 
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

			$share_member =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$shareOpenid}' ");

			if (empty($share_member)) { //分享人不存在
				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=601"); 
			} else {  //更新分享人信息
				if ($member['total_money']<=3000) {
					$share_member['current_money'] = $share_member['current_money'] + $activity['transmit_money'];
					
					$share_member['total_money'] = $share_member['total_money'] + $activity['transmit_money'];
				}

				pdo_update(bdyx_member,$share_member,array('openid' => $shareOpenid));

				$transmit = array('uniacid'=>$_W['uniacid'],

					'activity_id'=>$activity['id'],

					'share_openid'=>$shareOpenid,

					'my_openid'=>$myOpenid,

					'share_money'=>$activity['transmit_money'],

					'share_from'=>$shareFrom,

					);

				pdo_insert(bdyx_activity_transmit,$transmit);

				//判断分享人上一层是否存在
				$transmit = pdo_fetch("SELECT * FROM ".tablename('bdyx_activity_transmit')." WHERE uniacid = '{$uniacid}' and activity_id='2' and my_openid = '{$shareOpenid}'  ");
				//$transmit['share_openid']

				if (empty($transmit)) {
					header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
				} else{
					/*if ($share_pre_openid == $myOpenid) {

					}*/
					
					$share_pre_openid = $transmit['share_openid'];
					$share_member_pre =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$share_pre_openid}' ");
					if (empty($share_member_pre)) {
						//分享人的上一层 被删掉
						header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
					} else {
						//分享人的上一层 存在  加钱 5
						if ($member['total_money']<=3000) {
							$share_member_pre['current_money'] = $share_member_pre['current_money'] + 5;

							$share_member_pre['total_money'] = $share_member_pre['total_money'] + 5;
						}

						pdo_update(bdyx_member,$share_member_pre,array('openid' => $share_pre_openid));

						header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");//金币页面
					}
					
				}

				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=601"); 
			}



		} else {
			//已经点过链接
			/*if(strlen($member['mobile']) == 0) {
				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602"); 
			}*/

			$transmit = pdo_fetch("SELECT * FROM ".tablename('bdyx_activity_transmit')." WHERE uniacid = '{$uniacid}' and activity_id='2' and my_openid = '{$shareOpenid}'  ");

			$share_pre_o = $transmit['share_openid'];

			if (($share_pre_o == $myOpenid) || empty($share_pre_o)) {
				/*var_dump("expression");exit;*/
				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
				exit;
			}

			if ($myOpenid == $shareOpenid) {
				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
			}


			//判断是否提交过信息
			if (strlen($member['mobile']) == 0) {
				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=601");  //首页
			}
			
			 //分享人不存在
			$share_member =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$shareOpenid}' ");

			if (empty($share_member)) {
				//判断是否提交过信息
				if (strlen($member['mobile']) == 0) {
					header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=601");  //首页
				}

				header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=601"); // 首页
				exit; 
			} else {  //更新分享人信息
				if ($member['total_money']<=3000) {
					$share_member['current_money'] = $share_member['current_money'] + $activity['transmit_money'];
					
					$share_member['total_money'] = $share_member['total_money'] + $activity['transmit_money'];
				}

				pdo_update(bdyx_member,$share_member,array('openid' => $shareOpenid)); //加钱操作

				$transmit = array('uniacid'=>$_W['uniacid'],

					'activity_id'=>$activity['id'],

					'share_openid'=>$shareOpenid,

					'my_openid'=>$myOpenid,

					'share_money'=>$activity['transmit_money'],

					'share_from'=>$shareFrom,

					);

				pdo_insert(bdyx_activity_transmit,$transmit); //记录

				//检查分享人上一层     谁分享给分享人，是否存在
				$transmit = pdo_fetch("SELECT * FROM ".tablename('bdyx_activity_transmit')." WHERE uniacid = '{$uniacid}' and activity_id='2' and my_openid = '{$shareOpenid}'  ");
				//$transmit['share_openid']

				if (empty($transmit)) {
					header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
				} else{
					$share_pre_openid = $transmit['share_openid'];
					$share_member_pre =  pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and openid='{$share_pre_openid}' ");
					if (empty($share_member_pre)) {
						//分享人的上一层 被删掉
						header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");
					} else {
						//分享人的上一层 存在  加钱 5
						if ($member['total_money']<=3000) {
							$share_member_pre['current_money'] = $share_member_pre['current_money'] + 5;

							$share_member_pre['total_money'] = $share_member_pre['total_money'] + 5;
						}

						pdo_update(bdyx_member,$share_member_pre,array('openid' => $share_pre_openid));

						header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602");//金币页面
					}
					
				}

				/*header("Location: http://020.liyingkeji.com/app/index.php?i=47&c=entry&eid=602"); */
			}
		}
		
	} else {
		header("Location: http://www.tpy10.net/ewm.php?name=gh_51ea56e05ff8");
		exit;
	}

  	include $this->template('friend');
?>