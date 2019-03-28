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
if(isset($_POST['buy'])){
	if(isset($_POST['daili']) && isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
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
				exit("<script language='javascript'>alert('极验验证失败！');history.go(-1);</script>");
			}
		}else{  //服务器宕机,走failback模式
			if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
				$geetest=true;
			}else{
				exit("<script language='javascript'>alert('极验验证失败！');history.go(-1);</script>");
			}
		}
		//验证成功执行处理
		if ($geetest){
			$daili=daddslashes($_POST['daili']);
			$row=$DB->get_row("SELECT * FROM ".DB_PREFIX."_daili WHERE id=".$daili);
			if($row==''){
				exit("<script language='javascript'>alert('没有该代理！');history.go(-1);</script>");
			}
			if($udata['balance']<$row['money']){
				exit("<script language='javascript'>alert('您的余额不足本次扣费，请充值！');window.location.href='./recharge.php';</script>");
			}
			$balance=$udata['balance']-$row['money'];
			$sql="update ".DB_PREFIX."_user set daili='".$row['id']."',balance='".$balance."' where id='".$udata['id']."';";
			$DB->query($sql);
			exit("<script language='javascript'>alert('代理购买成功，快去搭建网站吧！');window.location.href='./site-add.php';</script>");
		}
	}
}else if(isset($_POST['update'])){
	if(isset($_POST['daili']) && isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
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
				exit("<script language='javascript'>alert('极验验证失败！');history.go(-1);</script>");
			}
		}else{  //服务器宕机,走failback模式
			if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
				$geetest=true;
			}else{
				exit("<script language='javascript'>alert('极验验证失败！');history.go(-1);</script>");
			}
		}
		//验证成功执行处理
		if ($geetest){
			$daili=daddslashes($_POST['daili']);
			$row=$DB->get_row("SELECT * FROM ".DB_PREFIX."_daili WHERE id=".$daili);
			$rows=$DB->get_row("SELECT * FROM ".DB_PREFIX."_daili WHERE id=".$udata['daili']);
			if($row==''){
				exit("<script language='javascript'>alert('没有此代理！');history.go(-1);</script>");
			}else if($row['id']<=$udata['daili']){
				exit("<script language='javascript'>alert('代理不能降级！');history.go(-1);</script>");
			}
			$money=$row['money']-$rows['money'];
			if($udata['balance']<$money){
				exit("<script language='javascript'>alert('您的余额不足本次扣费，请充值！');window.location.href='./recharge.php';</script>");
			}
			$balance=$udata['balance']-$money;
			$sql="update ".DB_PREFIX."_user set daili='".$row['id']."',balance='".$balance."' where id='".$udata['id']."';";
			$DB->query($sql);
			exit("<script language='javascript'>alert('代理升级成功，快去搭建网站吧！');window.location.href='./site-add.php';</script>");
		}
	}
}
?>
<?php require './header.php';?>
<?php if($udata['daili']==0){?>
<main id="main-container" style="min-height: 493px;"><div class="content">
<h2 class="content-heading">购买代理</h2>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">在线购买代理</h3>
<div class="block-options">
<button type="button" class="btn-block-option">
<i class="si si-wrench"></i>
</button>
</div>
</div>
<div class="block-content">
<div class="row justify-content-center py-20">
<div class="col-xl-6">
<form class="js-validation-bootstrap" action="daili.php" method="post" novalidate="novalidate">
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-select2">可购买代理级别 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<select class="form-control" id="val-select2" name="daili" data-placeholder="选择代理级别" >
<?php
$rows=$DB->query("SELECT * FROM ".DB_PREFIX."_daili WHERE action=1");
while($row = $DB->fetch($rows)){
	$discount=$row['discount']/10;
	echo '<option value="'.$row['id'].'">'.$row['name'].' - '.$row['money'].'元 / 可享受'.$discount.'折</option>';
}
?>
</select>
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-username">验证码 <span class="text-danger">*</span></label>
<div class="col-lg-8" id="embed-captcha">
<div id="wait" class="geektestshow">正在加载验证码......</div>
<label id="geektestnotice" class="geektesthide">请先完成验证</label>
</div>
</div>
<div class="form-group row">
<div class="col-lg-8 ml-auto">
<button type="submit" name="buy" id="embed-submit" class="btn btn-alt-primary">购买代理</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</main>
<?php }else{ ?>
<main id="main-container" style="min-height: 493px;"><div class="content">
<h2 class="content-heading">升级代理</h2>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">升级代理</h3>
<div class="block-options">
<button type="button" class="btn-block-option">
<i class="si si-wrench"></i>
</button>
</div>
</div>
<div class="block-content">
<div class="row justify-content-center py-20">
<?php 
$daili_level=$DB->get_row("select * from ".DB_PREFIX."_daili where id>".$udata['daili'].";");
if($daili_level==''){
?>
<div class="alert alert-success" role="alert">
	您是最高级别的代理，无需升级！		
</div>
<?php }else{ 
$daili=$DB->get_row("SELECT * FROM ".DB_PREFIX."_daili WHERE id=".$udata['daili']);	
$discount=$daili['discount']/10;
?>
<div class="col-xl-6">
<form class="js-validation-bootstrap" action="daili.php" method="post" novalidate="novalidate">
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-select2">当前代理级别 </label>
<div class="col-lg-8">
<div class="form-control"><?php echo $daili['name'];?> / 可享受搭建网站<?php echo $discount;?>折</div>
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-select2">可升级代理级别 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<select class="form-control" id="val-select2" name="daili" data-placeholder="选择代理级别" >
<?php
$rows=$DB->query("SELECT * FROM ".DB_PREFIX."_daili WHERE id>".$udata['daili']." and action=1");
while($row = $DB->fetch($rows)){
	$money=$row['money']-$daili['money'];
	$discount=$row['discount']/10;
	echo '<option value="'.$row['id'].'">'.$row['name'].' - '.$money.'元 / 可享受'.$discount.'折</option>';
}
?>
</select>
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-username">验证码 <span class="text-danger">*</span></label>
<div class="col-lg-8" id="embed-captcha">
<div id="wait" class="geektestshow">正在加载验证码......</div>
<label id="geektestnotice" class="geektesthide">请先完成验证</label>
</div>
</div>
<div class="form-group row">
<div class="col-lg-8 ml-auto">
<button type="submit" name="update" id="embed-submit" class="btn btn-alt-primary">立即升级</button>
</div>
</div>
</form>
</div>
<?php }?>
</div>
</div>
</div>
</div>
</main>
<?php }?>
<?php require './aside.php';?>
<footer id="page-footer" class="opacity-0">
<div class="content py-20 font-size-xs clearfix">
<div class="float-left">
<?php echo readconfig('copyright');?></div>
</div>
</footer></div><script>jQuery(function(){Codebase.helpers('table-tools');});</script>
<script src="assets/js/codebase.min-2.1.js"></script><script src="assets/js/plugins/select2/select2.full.min.js"></script>
<script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/js/plugins/jquery-validation/additional-methods.min.js"></script>
<script>jQuery(function(){Codebase.helpers('select2');});</script>
<script src="assets/js/pages/be_forms_validation.js"></script>
<script src="assets/js/gt.js"></script>
<script>
    var handlerEmbed = function (captchaObj) {
        $("#embed-submit").click(function (e) {
            var validate = captchaObj.getValidate();
            if (!validate) {
                $("#geektestnotice")[0].className = "geektestshow";
                setTimeout(function () {
                    $("#geektestnotice")[0].className = "geektesthide";
                }, 2000);
                e.preventDefault();
            }
        });
        // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
        captchaObj.appendTo("#embed-captcha");
        captchaObj.onReady(function () {
            $("#wait")[0].className = "geektesthide";
        });
        // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
    };
    $.ajax({
        // 获取id，challenge，success（是否启用failback）
        url: "../GeektestCaptcha.php?act=addsite&t=" + (new Date()).getTime(), // 加随机数防止缓存
        type: "get",
        dataType: "json",
        success: function (data) {
            console.log(data);
            // 使用initGeetest接口
            // 参数1：配置参数
            // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
            initGeetest({
                gt: data.gt,
                https: true,
                width: '100%',
                challenge: data.challenge,
                new_captcha: data.new_captcha,
                product: "popup", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                // 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
            }, handlerEmbed);
        }
    });
</script>
</body>
</html>