<?php
/**
 * Copyright (C) <2018>  辽宁微时光科技有限公司
 * Author 酸奶(qq348069510)
 * Email admin@vtimecn.com or 348069510@qq.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include("../include/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if(isset($_POST['recharge'])){
	if(isset($_POST['money']) && isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
		//极验验证
		$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
		$data = array(
				"user_id" => 'addsite', # 网站用户id
				"client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
				"ip_address" => getIp() # 请在此处传输用户请求验证时所携带的IP
				);
		if ($_SESSION['gtserver'] == 1) {   //服务器正常
    	$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
    		if ($result) {
    			$geetest=true;
			}else{
				exit("<script language='javascript'>alert('极验验证失败！');window.close();</script>");
			}
		}else{  //服务器宕机,走failback模式
			if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
				$geetest=true;
			}else{
				exit("<script language='javascript'>alert('极验验证失败！');window.close();</script>");
			}
		}
		//验证成功执行处理
		if ($geetest){
			$pay_type=daddslashes($_POST['pay_type']);
			$money=daddslashes($_POST['money']);
			if($pay_type=='alipay'){
				$pay_type='alipay';
			}else if($pay_type=='qpay'){
				$pay_type='qpay';
			}else if($pay_type=='wxpay'){
				$pay_type='wxpay';
			}else{
				exit("<script language='javascript'>alert('错误的支付方式！');window.close();</script>");
			}
			if($money<=0 || !is_numeric($money) || $money>100){
				exit("<script language='javascript'>alert('错误的金额，请输入1-100的金额！');window.close();</script>");
			}
			$money=sprintf("%.2f",$money);
			$ip=getIp();
			$address=getCity($ip);
			$order_on=date("YmdHis").mt_rand(100,999);
			$sql="insert into `".DB_PREFIX."_recharge` (`user`,`pay_type`,`money`,`addres`,`order_no`,`date`,`status`) values ('".$udata['id']."','".$pay_type."','".$money."','".$address."','".$order_on."','".$date."',0);";
			$DB->query($sql);
			$recharge_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_recharge WHERE order_no='$order_on' limit 1");
		}
	}
}else if(isset($_GET['id'])){
	$id=daddslashes($_GET['id']);
	$recharge_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_recharge WHERE id='$id' and user='".$udata['id']."' and status=0 limit 1");
	if(!$recharge_row){exit("<script language='javascript'>alert('无效订单，或此订单已支付！');history.go(-1);</script>");}
}else{
	exit('非法操作');
}
?>
<?php require './header.php';?>
<main id="main-container">
<div class="content">
    <h2 class="content-heading">充值中心</h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">订单支付</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-8">
                    <form class="js-validation-bootstrap" action="pay.php" method="post">
                        <div class="form-group row">
                        	<input type="hidden" name="id" value="<?php echo $recharge_row['id']; ?>">
                            <label class="col-lg-4 col-form-label" for="val-username">支付方式 <span class="text-danger">*</span></label>
                            <div class="col-lg-4">
                                <input type="radio" checked="checked" value="<?php echo $recharge_row['pay_type'];?>" name="pay_type"><img src="./assets/images/<?php echo $recharge_row['pay_type']; ?>.gif" width="135" height="45" alt="<?php echo pay_type($recharge_row['pay_type']); ?>在线支付" title="<?php echo pay_type($recharge_row['pay_type']); ?>在线支付">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="val-phoneus">订单号 <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" value="<?php echo $recharge_row['order_no'];?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="val-phoneus">充值金额 <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" value="<?php echo $recharge_row['money'];?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-8 ml-auto">
                                <button type="submit" name="pay" class="btn btn-alt-primary">立即充值</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
<?php require './aside.php';?>
<footer id="page-footer" class="opacity-0">
<div class="content py-20 font-size-xs clearfix">
<div class="float-left">
<?php echo readconfig('copyright');?></div>
</div>
</footer></div><script src="assets/js/codebase-1.2.min.js"></script>
<script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/js/plugins/jquery-validation/additional-methods.min.js"></script>
</body>
</html>