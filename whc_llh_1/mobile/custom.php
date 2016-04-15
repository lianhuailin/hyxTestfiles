<?php
	require_once "jssdk/jssdk.php";
	require_once "jssdk/WxPayJsApiPay.php";
	
	$uniacid=$_W['uniacid'];
	$activityId = 3;
	$recommends_code = $_GET['recommendcode'];
	/*echo "$recommends_code";*/
	$message = "谢谢您的承认,欢迎浏览公众号";
	$shareFrom == 'custom';
	$activity = pdo_fetchall("SELECT * FROM ".tablename('bdyx_activity')." WHERE uniacid = '{$uniacid}' and id='{$activityId}'")[0];

	$jsapi = new JsApiPay();
	$myOpenid = $jsapi->GetOpenid();
	/*echo $myOpenid; exit;*/
	//时间
	if (($activity['start_time']<time()) && ($activity['endtime']<time())) {
		/*echo "在时间活动内";*/
		$member = pdo_fetch("SELECT * FROM ".tablename('bdyx_member')." WHERE uniacid = '{$uniacid}' and recommends_code='{$recommends_code}' ");
		if (!empty($member)) {
			//兼职人员
			/*echo "是兼职人员";*/
			$transmit = pdo_fetch("SELECT * FROM ".tablename('bdyx_activity_transmit')." WHERE uniacid = '{$uniacid}' and activity_id='{$activityId}' and share_from = 'custom' and my_openid = '{$myOpenid}' ");
			if (count($transmit) == "1") {
				/*echo "未帮过该兼职";*/
				//兼职人员做多获得3000元  总金额
				if ($member['total_money']<3000) {
					$member['current_money'] = $member['current_money'] + $activity['transmit_money'];
						
					$member['total_money'] = $member['total_money'] + $activity['transmit_money'];
				}
				if (pdo_update(bdyx_member,$member,array('recommends_code' => $recommends_code))) {
				 	/*echo "成功添加兼职人员金额";*/
				 	$transmit = array('uniacid'=>$_W['uniacid'],

						'activity_id'=>$activity['id'],

						'my_openid'=>$myOpenid,

						'share_money'=>$activity['transmit_money'],

						'share_from'=>'custom',

						);

					if(pdo_insert(bdyx_activity_transmit,$transmit)) {
						/*echo "已记录信息";*/
						//成功添加分享记录
						$message = "谢谢您的承认,欢迎浏览公众号";
						/*echo "success insert";*/
					} else {
						/*echo "记录信息失败";*/
					}

				 } else {
				 	/*echo "添加兼职人员金额失败";*/
				 }

			} else {
				/*echo "已经帮过该兼职";*/
				$message = "您已经填过该推荐码";
			}
			
		} else {
			/*echo "不是兼职人员";*/$message = "该推荐码无效";
		}

	} else { //不在活动时间内
		/*echo "outtime"; exit;*/
		/*echo "不在活动时间内";*/
	}


  	include $this->template('think');
?>