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
if(isset($_POST['push_submit'])){
	if(isset($_POST['site_id']) && isset($_POST['new_user']) && isset($_POST['type']) && isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
		//极验验证
		$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
		$data = array(
				"user_id" => 'push', # 网站用户id
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
			$site_id = daddslashes($_POST['site_id']);
			$new_user = daddslashes($_POST['new_user']);
			$type = daddslashes($_POST['type']);
			$site_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_site WHERE sid='".$site_id."' and user='".$udata['id']."' and active=1 limit 1");
			if(!$site_row){
				exit("<script language='javascript'>alert('网站不存在,或不属于您的操作范围，或已被关停！');history.go(-1);</script>");
			}
			
			$push_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_push WHERE site_id='".$site_id."' and old_user='".$udata['id']."' and active=1 limit 1");
			if($push_row){
				exit("<script language='javascript'>alert('该网站正在PUSH中！');window.location.href='./push-list.php';</script>");
			}
			
			$user_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE username='".$new_user."' and active=1 limit 1");
			if(!$user_row){
				exit("<script language='javascript'>alert('用户名错误！');window.location.href='./push-list.php';</script>");
			}else if($user_row['id'] == $udata['id']){
				exit("<script language='javascript'>alert('不能PUSH给自己！');history.go(-1);</script>");
			}
			
			if($type == '1'){
				$type = '1';
			}else if($type == '2'){
				$type = '2';
			}else if($type == '3'){
				$type = '3';
			}else{
				exit("<script language='javascript'>alert('PUSH方式有误！');history.go(-1);</script>");
			}
			if($type == '1'){
				$sql="insert into `".DB_PREFIX."_push` (`site_id`,`old_user`,`new_user`,`type`,`date`,`active`) values ('".$site_row['sid']."','".$udata['id']."','".$user_row['id']."','".$type."','".$date."',1);";
				$DB->query($sql);
				exit("<script language='javascript'>alert('普通PUSH申请提交成功！');window.location.href='./push-list.php';</script>");
			}else if($type == '2'){
				$price = daddslashes($_POST['price']);
				if($price<=0 || !is_numeric($price)){
					exit("<script language='javascript'>alert('错误的金额！');window.close();</script>");
				}
				$price=sprintf("%.2f",$price);
				$sql="insert into `".DB_PREFIX."_push` (`site_id`,`old_user`,`new_user`,`type`,`price`,`date`,`active`) values ('".$site_row['sid']."','".$udata['id']."','".$user_row['id']."','".$type."','".$price."','".$date."',1);";
				$DB->query($sql);
				exit("<script language='javascript'>alert('带价PUSH申请提交成功！');window.location.href='./push-list.php';</script>");
			}else if($type == '3'){
				$sql="insert into `".DB_PREFIX."_push` (`site_id`,`old_user`,`new_user`,`type`,`date`,`active`) values ('".$site_row['sid']."','".$udata['id']."','".$user_row['id']."','".$type."','".$date."',2);";
				$DB->query($sql);
				$DB->query("UPDATE `".DB_PREFIX."_site` SET user=".$user_row['id']." WHERE sid='".$site_row['sid']."';");
				exit("<script language='javascript'>alert('极速PUSH申请提交成功，网站已PUSH到对方账户！');window.location.href='./push-list.php';</script>");
			}
		}
	}
}else if(isset($_GET['type'])){
	if($_GET['type'] == 'receive'){//接收
		$id = daddslashes($_GET['id']);
		$site = daddslashes($_GET['site']);
		$pwd = md5(daddslashes($_GET['pwd']));
		if($pwd != $udata['password']){
			exit("<script language='javascript'>alert('密码错误！');history.go(-1);</script>");
		}
		$push_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_push WHERE id='".$id."' limit 1");
		$site_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_site WHERE sid='".$push_row['site_id']."' limit 1");
		$old_user_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE id='".$push_row['old_user']."' limit 1");
		if(!$push_row){
			exit("<script language='javascript'>alert('此PUSH记录不存在！');history.go(-1);</script>");
		}else if($push_row['active'] != '1'){
			exit("<script language='javascript'>alert('此PUSH记录状态不允许接收！');history.go(-1);</script>");
		}
		if(!$site_row){
			exit("<script language='javascript'>alert('此网站不存在！');history.go(-1);</script>");
		}else if($site_row['active'] != '1'){
			exit("<script language='javascript'>alert('此网站已被冻结，不允许操作！');history.go(-1);</script>");
		}else if($site_row['domain'] != $site){
			exit("<script language='javascript'>alert('PUSH网站与请求参数不符！');history.go(-1);</script>");
		}else if($push_row['new_user'] != $udata['id']){
			exit("<script language='javascript'>alert('您对此PUSH请求无此操作权限！');history.go(-1);</script>");
		}
		if($push_row['type'] == '2'){
			if($push_row['price'] > $udata['balance']){
				exit("<script language='javascript'>alert('您的余额不足，请先充值！');history.go(-1);</script>");
			}
			$new_user_balance = sprintf("%.2f",$udata['balance']-$push_row['price']);
			$old_user_balance = sprintf("%.2f",$old_user_row['balance']+$push_row['price']);
			$DB->query("UPDATE `".DB_PREFIX."_push` SET active=2 WHERE id='".$push_row['id']."';");
			$DB->query("UPDATE `".DB_PREFIX."_user` SET balance='".$new_user_balance."' WHERE id='".$push_row['new_user']."';");
			$DB->query("UPDATE `".DB_PREFIX."_user` SET balance='".$old_user_balance."' WHERE id='".$push_row['old_user']."';");
			$DB->query("UPDATE `".DB_PREFIX."_site` SET user=".$push_row['new_user']." WHERE sid='".$site_row['sid']."';");
			exit("<script language='javascript'>alert('网站接收成功！');window.location.href='./push-list.php';</script>");
		}else{
			$DB->query("UPDATE `".DB_PREFIX."_push` SET active=2 WHERE id='".$push_row['id']."';");
			$DB->query("UPDATE `".DB_PREFIX."_site` SET user=".$push_row['new_user']." WHERE sid='".$site_row['sid']."';");
			exit("<script language='javascript'>alert('网站接收成功！');window.location.href='./push-list.php';</script>");
		}
	}else if($_GET['type'] == 'refused'){ //拒收
		$id = daddslashes($_GET['id']);
		$site = daddslashes($_GET['site']);
		$pwd = md5(daddslashes($_GET['pwd']));
		if($pwd != $udata['password']){
			exit("<script language='javascript'>alert('密码错误！');history.go(-1);</script>");
		}
		$push_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_push WHERE id='".$id."' limit 1");
		$site_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_site WHERE sid='".$push_row['site_id']."' limit 1");
		if(!$push_row){
			exit("<script language='javascript'>alert('此PUSH记录不存在！');history.go(-1);</script>");
		}else if($push_row['active'] != '1'){
			exit("<script language='javascript'>alert('此PUSH记录状态不允许拒收！');history.go(-1);</script>");
		}
		if(!$site_row){
			exit("<script language='javascript'>alert('此网站不存在！');history.go(-1);</script>");
		}else if($site_row['active'] != '1'){
			exit("<script language='javascript'>alert('此网站已被冻结，不允许操作！');history.go(-1);</script>");
		}else if($site_row['domain'] != $site){
			exit("<script language='javascript'>alert('PUSH网站与请求参数不符！');history.go(-1);</script>");
		}else if($push_row['new_user'] != $udata['id']){
			exit("<script language='javascript'>alert('您对此PUSH请求无此操作权限！');history.go(-1);</script>");
		}
		$DB->query("UPDATE `".DB_PREFIX."_push` SET active=3 WHERE id='".$push_row['id']."';");
		exit("<script language='javascript'>alert('网站拒收成功！');window.location.href='./push-list.php';</script>");
	}else if($_GET['type'] == 'undo'){ //撤销
		$id = daddslashes($_GET['id']);
		$site = daddslashes($_GET['site']);
		$pwd = md5(daddslashes($_GET['pwd']));
		if($pwd != $udata['password']){
			exit("<script language='javascript'>alert('密码错误！');history.go(-1);</script>");
		}
		$push_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_push WHERE id='".$id."' limit 1");
		$site_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_site WHERE sid='".$push_row['site_id']."' limit 1");
		if(!$push_row){
			exit("<script language='javascript'>alert('此PUSH记录不存在！');history.go(-1);</script>");
		}else if($push_row['active'] != '1'){
			exit("<script language='javascript'>alert('此PUSH记录状态不允许撤销请求！');history.go(-1);</script>");
		}
		if(!$site_row){
			exit("<script language='javascript'>alert('此网站不存在！');history.go(-1);</script>");
		}else if($site_row['active'] != '1'){
			exit("<script language='javascript'>alert('此网站已被冻结，不允许操作！');history.go(-1);</script>");
		}else if($site_row['domain'] != $site){
			exit("<script language='javascript'>alert('PUSH网站与请求参数不符！');history.go(-1);</script>");
		}else if($push_row['old_user'] != $udata['id'] || $site_row['user'] != $udata['id']){
			exit("<script language='javascript'>alert('您对此PUSH请求和网站无此操作权限！');history.go(-1);</script>");
		}
		$DB->query("UPDATE `".DB_PREFIX."_push` SET active=4 WHERE id='".$push_row['id']."';");
		exit("<script language='javascript'>alert('PUSH撤销成功！');window.location.href='./push-list.php';</script>");
	}else{
		exit('参数不正确');
	}
}else if(isset($_POST['query'])){
	$user_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE username='".daddslashes($_POST['query'])."' limit 1");
	if($user_row['id'] == $udata['id']){
		exit(json_encode(array("code" => "0","msg" => "不能PUSH给自己")));
	}if($user_row['active'] == '0'){
		exit(json_encode(array("code" => "0","msg" => "该账户已被冻结")));
	}if($user_row){
		exit(json_encode(array("code" => "1","msg" => "用户名正确","name" => $user_row['name'])));
	}else{
		exit(json_encode(array("code" => "0","msg" => "用户不存在")));
	}
}else if(isset($_GET['site_id'])){
	$site_id = daddslashes($_GET['site_id']);
	$site_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_site WHERE sid='".$site_id."' and user='".$udata['id']."' and active=1 limit 1");
	if(!$site_row){
		exit("<script language='javascript'>alert('网站不存在,或不属于您的操作范围，或已被关停！');history.go(-1);</script>");
	}
	$push_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_push WHERE site_id='".$site_id."' and old_user='".$udata['id']."' and active=1 limit 1");
	if($push_row){
		exit("<script language='javascript'>alert('该网站正在PUSH中！');window.location.href='./push-list.php';</script>");
	}
?>
<?php require './header.php';?>
<main id="main-container" style="min-height: 493px;"><div class="content">
<h2 class="content-heading">网站PUSH</h2>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">在线网站PUSH</h3>
<div class="block-options">
<button type="button" class="btn-block-option">
<i class="si si-wrench"></i>
</button>
</div>
</div>
<div class="block-content">
<div class="row justify-content-center py-20">
<div class="col-xl-6">
<form class="js-validation-bootstrap" action="push.php" method="post" novalidate="novalidate">
<input type="hidden" name="site_id" value="<?php echo $site_row['sid'];?>">
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-select2">PUSH的网站 </label>
<div class="col-lg-8">
<div class="form-control"><?php echo $site_row['domain'];?>.<?php echo readconfig('domain');?></div>
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-username">对方用户名 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="text" class="form-control" id="new_user" name="new_user" placeholder="请填写正确的用户名！">
<div id="query_user" style="display: none;"></div>
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-select2">PUSH方式 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<select class="form-control" id="val-select2" name="type" style="width: 100%;" data-placeholder="选择PUSH方式" onchange="push_price(this.value)">
<option value="1">普通PUSH</option>
<option value="2">带价PUSH</option>
<option value="3">极速PUSH</option>
</select>
</div>
</div>
<div class="form-group row" id="price" style="display: none;">
<label class="col-lg-4 col-form-label" for="val-username">索要金额 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="text" class="form-control" name="price" value="1.00" placeholder="请正确填写金额，带价PUSH方式必填">
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
<button type="submit" name="push_submit" id="embed-submit" class="btn btn-alt-primary">开始搭建</button>
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
</footer></div><script>jQuery(function(){Codebase.helpers('table-tools');});</script>
<script src="assets/js/codebase.min-2.1.js"></script><script src="assets/js/plugins/select2/select2.full.min.js"></script>
<script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/js/plugins/jquery-validation/additional-methods.min.js"></script>
<script>jQuery(function(){Codebase.helpers('select2');});</script>
<script src="assets/js/pages/be_forms_validation.js"></script>
<script src="assets/js/gt.js"></script>
<script>
	function push_price(val){
		var price =  $("#price");
    if(val == 1){
       $(price).hide();
    }
    if(val == 2){
       $(price).show();
    }
    if(val == 3){
       $(price).hide();
    }
}
    
</script>
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
        url: "../GeektestCaptcha.php?act=push&t=" + (new Date()).getTime(), // 加随机数防止缓存
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
<script src="assets/layer/layer.js"></script>
<script>
	var new_user=document.getElementById("new_user");
	var query_user =  $("#query_user");
	new_user.onchange=function(){
		if(new_user.value != ''){
			$.ajax({
				url: "./push.php", 
				type: "post",
				data: { query: new_user.value},
				dataType: "json",
				success: function (data) {
					$(query_user).show();
					if(data.code == 1){
						$(query_user).html('<p style=\'color:green\'>用户昵称：' + data.name + '</p>');
					}else{
						$(query_user).html('<p style=\'color:red\'>' + data.msg + '</p>');
					}
				}
			});
		}else{
			$(query_user).hide();
		}
	}
</script>
</body>
</html>
<?php
}else{
	exit('404 Not Found!');
}
?>