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
//if($udata['balance']>0){}else exit("<script language='javascript'>alert('您的余额为0或已欠费，请充值！');window.location.href='./recharge.php';</script>");
if(isset($_POST['add'])){
	if(isset($_POST['domain']) && isset($_POST['optd']) && isset($_POST['program']) && isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
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
			$domain=daddslashes($_POST['domain']);
			$optd=daddslashes($_POST['optd']);
			$program=daddslashes($_POST['program']);
			$program_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_program WHERE install='$program' and active=1 limit 1");
			if(!$program_row){exit("<script language='javascript'>alert('网站程序不存在！');history.go(-1);</script>");}
			if($program_row['daili_price']==1 && $udata['daili']!=0){
				$daili=$DB->get_row("SELECT * FROM ".DB_PREFIX."_daili WHERE id=".$udata['daili']." and action=1");
				$price=sprintf("%.2f",$program_row['price']*$daili['discount']*0.01);
			}else{
				$price=$program_row['price'];
			}
			if($udata['balance']<$price){
				exit("<script language='javascript'>alert('您的余额不足本次扣费，请充值！');window.location.href='./recharge.php';</script>");
			}
			if($optd!='no'){
				$optdomain = $domain . '.' . $optd;
			}
			$password = substr(md5(uniqid().rand(1,10000)),16);
			$authcode = readconfig('authcode');
			$rand = rand(1,9999);
			$do = 'add_vh';
			$skey = md5($do.$authcode.$rand);
			$epurl = readconfig('epurl');
			$ip = readconfig('epip');
			$epid = $program_row['product_id'];
			$get = 'http://'.$epurl.'/api/?c=whm&a='.$do.'&r='.$rand.'&s='.$skey.'&name='.$domain.'&passwd='.$password.'&init=1&json=1&product_id='.$epid.'&month=12';
			$addsite = file_get_contents($get);
			if($addsite=json_decode($addsite,true)) {
				function js($val = null, $href = null){
					$href = $href ? 'window.location.href="' . $href . '";' : 'history.go(-1);';
				 	exit('<script language="javascript">alert("'.$val.'");' . $href . '</script>');
				}
			if($addsite['result'] != 200) {
				js('创建出错,请换个前缀试试~');
			}
			
			if ($optdomain){
				// 绑定域名操作

				// 登录获取Cookies
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php?c=session&a=login');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('username' => $domain, 'passwd' => $password)));
				curl_setopt($ch, CURLOPT_HEADER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				$p = curl_exec($ch);
				curl_close($ch);
				preg_match('/PHPSESSID=(.{26})/i', $p, $matches);
				$cookie = $matches[1];

				// 首页
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $cookie));
				$p2 = curl_exec($ch);
				curl_close($ch);

				// 绑定
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php?c=domain&a=add&domain=' . urlencode($optdomain) . '&subdir=%2Fwwwroot&replace=1');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $cookie));
				$p3 = curl_exec($ch);
				curl_close($ch);
				
				if ($p3 != '成功'){
					file_get_contents('http://'.$epurl.'/api/?c=whm&a=del_vh&r='.$rand.'&s='.md5('del_vh'.$authcode.$rand).'&name='.$domain);
					js('备用域名绑定失败, 原因:' . $p3 . '');
				}
			}
			$ftp = new ftp($ip,21,$domain,$password);     // 打开FTP连接 
			$ftp->up_file('./install/onekey_install.php','wwwroot/install.php');     // 上传文件 
			$ftp->close();
			$backdata = json_decode(file_get_contents('http://'.$domain.'.'.readconfig('domain').'/install.php?program='.$program.'&domain='.$domain.'.'.readconfig('domain').'&user='.$domain.'&password='.$password),true);
			if ($backdata['code']!=1){
				file_get_contents('http://'.$epurl.'/api/?c=whm&a=del_vh&r='.$rand.'&s='.md5('del_vh'.$authcode.$rand).'&name='.$domain);
                js('网站程序安装失败，错误信息：'.$backdata['msg']);
            }
			
            $site = $DB->query("INSERT INTO `".DB_PREFIX."_site` (`domain`,`optdomain`,`active`,`user`,`passwd`,`program`,`price`) VALUES ('".$domain."','".$optdomain."',1,'".$udata['id']."','".$password."','".$program_row['id']."','".$price."');");
            if ( ! $site){
                file_get_contents('http://'.$epurl.'/api/?c=whm&a=del_vh&r='.$rand.'&s='.md5('del_vh'.$authcode.$rand).'&name='.$domain);
                js('站点信息载入数据库出错');
            }
			$balance=$udata['balance']-$price;
			$sql="update ".DB_PREFIX."_user set balance='".$balance."' where id='".$udata['id']."';";
			$DB->query($sql);
			js('搭建成功, 域名:' . $domain . '.' . readconfig('domain') . ($optdomain ? ', 备用域名:' . $optdomain : null), 'site-list.php');

			}
		}
	}
}
?>
<?php require './header.php';?>
<main id="main-container" style="min-height: 493px;"><div class="content">
<h2 class="content-heading">搭建网站</h2>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">在线搭建网站</h3>
<div class="block-options">
<button type="button" class="btn-block-option">
<i class="si si-wrench"></i>
</button>
</div>
</div>
<div class="block-content">
<div class="row justify-content-center py-20">
<div class="col-xl-6">
<form class="js-validation-bootstrap" action="site-add.php" method="post" novalidate="novalidate">
<?php
if($udata['daili']!=0){
	$daili=$DB->get_row("SELECT * FROM ".DB_PREFIX."_daili WHERE id=".$udata['daili']);	
	$discount=$daili['discount']/10;
?>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-select2">代理优惠 </label>
<div class="col-lg-8">
<div class="form-control">您可享受搭建非授权类网站<?php echo $discount;?>折优惠</div>
</div>
</div>
<?php }?>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-username">域名前缀 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="text" class="form-control" name="domain" placeholder="请填写域名前缀,默认提供的二级域名后缀为<?=$config['domain']?>" required>
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-select2">可选域名后缀 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<select class="form-control" id="val-select2" name="optd" data-placeholder="选择域名后缀" >
<option value="no">不使用</option>
<?php
$domain_list = explode("\n", str_replace(array(" ", ",", "|"), "\n", readconfig('optdomain')));
foreach ($domain_list as $row){
	if ($row)
	echo '<option value="'.$row.'">'.$row.'</option>';
}
?>
</select>
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-select2">网站程序 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<select class="form-control" id="val-select2" name="program" style="width: 100%;" data-placeholder="选择网站程序" >
<?php
$rows = $DB->query("SELECT * FROM ".DB_PREFIX."_program where active=1");
foreach($rows as $row){
	if($row['daili_price']==1 && $udata['daili']!=0){
		$daili=$DB->get_row("SELECT * FROM ".DB_PREFIX."_daili WHERE id=".$udata['daili']." and action=1");
		$price=sprintf("%.2f",$row['price']*$daili['discount']*0.01);
		$price=$price.'元/年';
	}else{
		$price=$row['price'].'元/年';
	}
	
?>
<option value="<?=$row['install']?>"><?=$row['name']?> - <?=$price;?></option>
<?php } ?>
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
<button type="submit" name="add" id="embed-submit" class="btn btn-alt-primary">开始搭建</button>
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