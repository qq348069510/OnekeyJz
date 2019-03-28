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

header('Content-Type: text/html; charset=UTF-8');
include("../include/common.php");
if(isset($_POST['login-username']) && isset($_POST['login-password']) && isset($_POST['login'])){
	$user=daddslashes($_POST['login-username']);
	$pass=daddslashes($_POST['login-password']);
	$row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE username='$user' limit 1");
	if($row['username']=='') {
		exit("<script language='javascript'>alert('此用户不存在');history.go(-1);</script>");
	}elseif (md5($pass) != $row['password']) {
		exit("<script language='javascript'>alert('用户名或密码不正确！');history.go(-1);</script>");
	}elseif($row['username']==$user && $row['password']==md5($pass)){
		if(isset($_POST['ispersis'])){
			setcookie("islogin", "1", time() + 604800);
			setcookie("console_user", base64_encode($user), time() + 604800);
			setcookie("console_pass", sha1(md5($pass).LOGIN_KEY), time() + 604800);
			$realip=getIp();
			$address=getCity($realip);
			$ua=$_SERVER['HTTP_USER_AGENT'];
			$device=get_device($ua);
			$time=date("Y-m-d H:i:s");
			$sql="INSERT INTO `".DB_PREFIX."_ip` (`user`, `ip`, `addres`, `platform`, `date`) VALUES ('{$row['id']}','{$realip}','{$address}','{$device}','{$time}');";
			$DB->query($sql);
			exit("<script language='javascript'>alert('登陆成功！');window.location.href='./';</script>");
		}else{
			$_SESSION['islogin']=1;
			$_SESSION['console_user']=base64_encode($user);
			$_SESSION['console_pass']=sha1(md5($pass).LOGIN_KEY);
			$realip=getIp();
			$address=getCity($realip);
			$ua=$_SERVER['HTTP_USER_AGENT'];
			$device=get_device($ua);
			$time=date("Y-m-d H:i:s");
			$sql="INSERT INTO `".DB_PREFIX."_ip` (`user`, `ip`, `addres`, `platform`, `date`) VALUES ('{$row['id']}','{$realip}','{$address}','{$device}','{$time}');";
			$DB->query($sql);
			exit("<script language='javascript'>alert('登陆成功！');window.location.href='./';</script>");
		}
	}
}elseif(isset($_GET['logout'])){
	setcookie("islogin", "", time() - 604800);
	setcookie("console_user", "", time() - 604800);
	setcookie("console_pass", "", time() - 604800);
	unset($_SESSION['islogin']);
	unset($_SESSION['console_user']);
	unset($_SESSION['console_pass']);
	exit("<script language='javascript'>alert('您已成功注销本次登陆！');window.location.href='./login.php';</script>");
}elseif($islogin==1){
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}
?>
<!doctype html>
<!--[if lte IE 9]>     <html lang="zh-cn" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="zh-cn" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>一键建站系统-登录用户中心</title>
        <meta name="description" content="一键建站系统">
        <meta name="keyword" content="一键建站系统">
        <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <link rel="stylesheet" id="css-main" href="assets/css/codebase.min-2.1.css">
    </head>
<body><div id="page-container" class="main-content-boxed">
                <main id="main-container">
<div class="bg-body-dark bg-pattern" style="background-image: url('assets/media/various/bg-pattern-inverse.png');">
    <div class="row mx-0 justify-content-center">
        <div class="hero-static col-lg-6 col-xl-4">
            <div class="content content-full overflow-hidden">
                <div class="py-30 text-center">
                    <a class="link-effect font-w700" href="index.php">
                        <i class="si si-fire"></i>
                        <span class="font-size-xl text-primary-dark">建站系统</span><span class="font-size-xl">控制台</span>
                    </a>
                </div>
                <form class="js-validation-signin" action="login.php" method="post">
                    <div class="block block-themed block-rounded block-shadow">
                        <div class="block-header bg-gd-dusk">
                            <h3 class="block-title">使用你的账户登录用户中心</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option">
                                    <a title="刷新" onclick="document.cookie='cf_use_ob=0;path=/';window.location.reload();return false;"><i class="si si-refresh"></i></a>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="login-username">用户名</label>
                                    <input type="text" class="form-control" id="login-username" name="login-username">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="login-password">密码</label>
                                    <input type="password" class="form-control" id="login-password" name="login-password">
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-sm-6 d-sm-flex align-items-center push">
                                    <div class="custom-control custom-checkbox mr-auto ml-0 mb-0">
                                        <input type="checkbox" class="custom-control-input" id="login-remember-me" name="ispersis">
                                        <label class="custom-control-label" for="login-remember-me">记住我(7天免登录)</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-right push">
                                    <button type="submit" name="login" class="btn btn-alt-primary">
                                        <i class="si si-login mr-10"></i> 立即登录
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="block-content bg-body-light">
                            <div class="form-group text-center">
                                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="reg.php">
                                    <i class="fa fa-plus mr-5"></i> 立即注册
                                </a>
                                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=9571564&site=qq&menu=yes">
                                    <i class="fa fa-warning mr-5"></i> 忘记密码
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </main>
    </div>
<script src="assets/js/codebase.min-2.1.js"></script><script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/js/pages/op_auth_signin.js"></script>
</body>
</html>