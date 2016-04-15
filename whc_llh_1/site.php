<?php

/**

 * whc_llh_1模块微站定义

 *

 * @author llh

 * @url http://020.liyingkeji.com/

 */

defined('IN_IA') or exit('Access Denied');



class Whc_llh_1ModuleSite extends WeModuleSite {



	public function doMobileIndex() {

		$this->__mobile(__FUNCTION__);

	}

	public function doMobileFriend() {

		$this->__mobile(__FUNCTION__);

	}

	public function doMobileTimeline() {

		$this->__mobile(__FUNCTION__);

	}


	public function doMobileRecommendcode() {

		$this->__mobile(__FUNCTION__);

	}

	public function doMobileCustom() {

		$this->__mobile(__FUNCTION__);

	}

	public function doMobileBecomeparttime() {

		$this->__mobile(__FUNCTION__);

	}

	public function doMobilePhone() {

		$this->__mobile(__FUNCTION__);

	}

	public function doMobileMoney() {

		$this->__mobile(__FUNCTION__);

	}


	//------------------------------------------------web-----------------------------------
	public function doWebMember() {

		$this->__web(__FUNCTION__);

	}

	public function doWebParttime() {

		$this->__web(__FUNCTION__);

	}

	public function doWebAttention() {

		$this->__web(__FUNCTION__);

	}

	public function __web($f_name){

		global $_W,$_GPC;

		checklogin();

		$uniacid=$_W['uniacid'];

		load()->func('tpl');

		include_once  'web/'.strtolower(substr($f_name,5)).'.php';

	}

	public function __mobile($f_name){

		global $_W,$_GPC;

		checkauth();

		$uniacid=$_W['uniacid'];

/*		$share_data = pdo_fetch("SELECT * FROM ".tablename('feng_wechat')." WHERE uniacid = '{$uniacid}'");*/

		$share_data = $this->module['config'];

		//$this->

		$to_url = $_W['siteroot'].'app/'.$this->createMobileUrl('attention', array());

		include_once  'mobile/'.strtolower(substr($f_name,8)).'.php';

	}

}