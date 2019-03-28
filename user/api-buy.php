<?php
include("../include/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if($apiinfo!='')exit("<script language='javascript'>alert('您已开通API权限，无需再次开通！');window.location.href='./api.php';</script>");
if(isset($_POST['buy']) && isset($_POST['password'])){
	$password = md5(daddslashes($_POST['password']));
	if($password!='' && $password==$udata['password']){
		if($udata['gift_api']!='1'){
			$price = readconfig('apiprice');
			if($udata['balance']<$price)exit("<script language='javascript'>alert('您的余额不足本次扣费，请充值！');window.location.href='./recharge.php';</script>");
			$apiid=date('YmdH').rand(1000,9999);
			$apikey=md5(time().rand(10000,9999999));
			$sql="insert into `".DB_PREFIX."_api` (`user`,`apiid`,`apikey`,`active`) values ('".$udata['id']."','".$apiid."','".$apikey."',1)";
			if($DB->query($sql)){
				$balance=$udata['balance']-$price;
				$DB->query("update ".DB_PREFIX."_user set balance='".$balance."' where id='".$udata['id']."';");
				exit("<script language='javascript'>alert('API开通成功！');window.location.href='./api.php';</script>");
			}else{
				exit("<script language='javascript'>alert('未知错误，请联系管理员！');history.go(-1);</script>");
			}
		}else{
			$apiid=date('YmdH').rand(1000,9999);
			$apikey=md5(time().rand(10000,9999999));
			$sql="insert into `".DB_PREFIX."_api` (`user`,`apiid`,`apikey`,`active`) values ('".$udata['id']."','".$apiid."','".$apikey."',1)";
			if($DB->query($sql)){
				$DB->query("update ".DB_PREFIX."_user set gift_api='0' where id='".$udata['id']."';");
				exit("<script language='javascript'>alert('API开通成功！');window.location.href='./api.php';</script>");
			}else{
				exit("<script language='javascript'>alert('未知错误，请联系管理员！');history.go(-1);</script>");
			}
		}
	}else{
		exit("<script language='javascript'>alert('密码不正确！');history.go(-1);</script>");
	}
}
?>
<?php require './header.php';?>
<main id="main-container">
<div class="content">
    <h2 class="content-heading">API 权限</h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">开通权限</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-8">
                    <form class="js-validation-bootstrap" action="" method="post">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label" >收费标准 <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            	<?php
                            	if($udata['gift_api']!=1){
                            		echo '<input type="text" class="form-control" value="API权限开通费用'.readconfig('apiprice').'元，可配合代理功能使用。" readonly >';
                            	}else{
                            		echo '<input type="text" class="form-control" value="您是内测用户，享有0元开通API特权，可配合代理功能使用。" readonly >';
                            	}
                            	?>
                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label" >登录密码 <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="password" class="form-control" name="password" placeholder="请输入密码进行验证" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-10 ml-auto">
                                <button type="submit" name="buy" id="embed-submit" class="btn btn-alt-primary">立即开通</button>
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