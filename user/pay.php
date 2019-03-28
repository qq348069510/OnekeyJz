<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>在线支付</title>
</head>
<?php
include("../include/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if(isset($_POST['pay']) && isset($_POST['id'])){
	$id=daddslashes($_POST['id']);
	$recharge_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_recharge WHERE id='$id' and user='".$udata['id']."' and status=0 limit 1;");
	if(!$recharge_row){exit("<script language='javascript'>alert('无效订单，或此订单已支付！');history.go(-1);</script>");}
	if($recharge_row['pay_type']=='alipay'){
		$type='alipay';
	}else if($recharge_row['pay_type']=='wxpay'){
		$type='wxpay';
	}else if($recharge_row['pay_type']=='qpay'){
		$type='qqpay';
	}else{
		exit('错误的支付方式！');
	}
require_once("../include/epay/epay.config.php");
require_once("../include/epay/epay_submit.class.php");
/**************************请求参数**************************/
        $notify_url = "http://".$_SERVER['HTTP_HOST']."/user/epay_notify_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = "http://".$_SERVER['HTTP_HOST']."/user/epay_return_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //商户订单号
        $out_trade_no = $recharge_row['order_no'];
        //商户网站订单系统中唯一订单号，必填

        //商品名称
        $name = '账号'.$udata['username'].'余额充值';
		//付款金额
        $money = $recharge_row['money'];
		//站点名称
        $sitename = '一键建站';
        //必填

        //订单描述


/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
		"pid" => trim($alipay_config['partner']),
		"type" => $type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"out_trade_no"	=> $out_trade_no,
		"name"	=> $name,
		"money"	=> $money,
		"sitename"	=> $sitename
);

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter);
echo $html_text;
}else{
	exit('非法访问');
}
?>
</body>
</html>