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
	if(isset($_POST['data'])){
	$name=daddslashes($_POST['name']);
	$qq=daddslashes($_POST['qq']);
	$email=daddslashes($_POST['email']);
	$sql="update ".DB_PREFIX."_user set name='$name',qq='$qq',email='$email' where id='{$udata['id']}';";
	$DB->query($sql);
	exit("<script language='javascript'>alert('资料修改成功！');window.location.href='./modify.php';</script>");
}else if(isset($_POST['passwd'])){
	$oldpass=daddslashes($_POST['oldpass']);
	if($udata['password'] == md5($oldpass)){
		$newpass=daddslashes($_POST['newpass']);
		$checkpass=daddslashes($_POST['checkpass']);
		if($newpass==$checkpass){
			if($newpass==''){exit("<script language='javascript'>alert('新密码不允许为空！');history.go(-1);</script>");}
			$newpass=md5($newpass);
			$sql="update ".DB_PREFIX."_user set password='$newpass' where id='{$udata['id']}';";
			$DB->query($sql);
			setcookie("islogin", "", time() - 604800);
			setcookie("console_user", "", time() - 604800);
			setcookie("console_pass", "", time() - 604800);
			unset($_SESSION['islogin']);
			unset($_SESSION['console_user']);
			unset($_SESSION['console_pass']);
			exit("<script language='javascript'>alert('您的密码已修改成功，请使用新密码重新登陆！');window.location.href='./login.php';</script>");
		}else{
			exit("<script language='javascript'>alert('两次密码输入不一致，验证新密码失败！');history.go(-1);</script>");
		}
	}else{
		exit("<script language='javascript'>alert('旧密码不正确！');history.go(-1);</script>");
	}
}
?>
<?php require './header.php';?>
<main id="main-container" style="min-height: 493px;"><div class="content">
<h2 class="content-heading">修改资料</h2>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">账户资料</h3>
<div class="block-options">
<button type="button" class="btn-block-option">
<i class="si si-wrench"></i>
</button>
</div>
</div>
<div class="block-content">
<div class="row justify-content-center py-20">
<div class="col-xl-6">
<form class="js-validation-bootstrap" action="modify.php" method="post" novalidate="novalidate">
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-username">用户名 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="text" class="form-control" value="<?php echo $udata['username'];?>" readonly>
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-username">昵称 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="text" class="form-control" name="name" value="<?php echo $udata['name'];?>" placeholder="昵称用于在控制台展示">
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-username">QQ <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="text" class="form-control" name="qq" value="<?php echo $udata['qq'];?>" placeholder="QQ用于后台显示头像">
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-username">邮箱 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="email" class="form-control" name="email" value="<?php echo $udata['email'];?>" placeholder="邮箱用于接收通知">
</div>
</div>
<div class="form-group row">
<div class="col-lg-8 ml-auto">
<button type="submit" name="data" class="btn btn-alt-primary">修改资料</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">账户密码</h3>
<div class="block-options">
<button type="button" class="btn-block-option">
<i class="si si-wrench"></i>
</button>
</div>
</div>
<div class="block-content">
<div class="row justify-content-center py-20">
<div class="col-xl-6">
<form class="js-validation-bootstrap" action="modify.php" method="post" novalidate="novalidate">
<div class="form-group row">
<label class="col-lg-4 col-form-label">旧密码 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="password" class="form-control" name="oldpass" placeholder="请输入旧密码">
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label">新密码 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="password" class="form-control" name="newpass" placeholder="请输入新密码">
</div>
</div>
<div class="form-group row">
<label class="col-lg-4 col-form-label">验证新密码 <span class="text-danger">*</span></label>
<div class="col-lg-8">
<input type="password" class="form-control" name="checkpass" placeholder="请再次输入新密码">
</div>
</div>
<div class="form-group row">
<div class="col-lg-8 ml-auto">
<button type="submit" name="passwd" class="btn btn-alt-primary">修改密码</button>
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
<?php echo readconfig('copyright');?>
</div>
</div>
</footer></div><script>jQuery(function(){Codebase.helpers('table-tools');});</script>
<script src="assets/js/codebase-1.2.min.js"></script><script src="assets/js/plugins/select2/select2.full.min.js"></script>
<script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/js/plugins/jquery-validation/additional-methods.min.js"></script>
<script>jQuery(function(){Codebase.helpers('select2');});</script>
<script src="assets/js/pages/be_forms_validation.js"></script>
</body>
</html>