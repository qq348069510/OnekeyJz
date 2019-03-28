<?php
include("../include/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if($apiinfo=='')exit("<script language='javascript'>alert('您暂未开通API权限，正在跳转到开通页面！');window.location.href='./api-buy.php';</script>");
if(isset($_GET['act'])){
	if($_GET['act']=='Rekey' && isset($_GET['password'])){
		$password = daddslashes($_GET['password']);
		if($password==$udata['password'] && $password!=''){
			$apikey=md5(time().rand(10000,9999999));
			$sql="update ".DB_PREFIX."_api set apikey='".$apikey."' where user='".$udata['id']."';";
			if($DB->query($sql)){
				exit("<script language='javascript'>alert('APIKEY重置成功！');window.location.href='./api.php';</script>");
			}else{
				exit("<script language='javascript'>alert('未知错误，请联系管理员！');history.go(-1);</script>");
			}
		}else{
			exit("<script language='javascript'>alert('密码不正确，验证失败！');history.go(-1);</script>");
		}
	}else if($_GET['act']=='ReToken' && isset($_GET['password'])){
		$password = daddslashes($_GET['password']);
		if($password==$udata['password'] && $password!=''){
			if($apiinfo['token']=='' || $apiinfo['token']==NULL)exit("<script language='javascript'>alert('暂未生成Token,无法清空！');history.go(-1);</script>");
			$sql="update ".DB_PREFIX."_api set token='' where user='".$udata['id']."';";
			if($DB->query($sql)){
				exit("<script language='javascript'>alert('Token清空成功！');window.location.href='./api.php';</script>");
			}else{
				exit("<script language='javascript'>alert('未知错误，请联系管理员！');history.go(-1);</script>");
			}
		}else{
			exit("<script language='javascript'>alert('密码不正确，验证失败！');history.go(-1);</script>");
		}
	}
}
?>
<?php require './header.php';?>
<main id="main-container">
<div class="content">
    <h2 class="content-heading">API 权限</h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">API管理</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
      <!--API公告 START-->
<div class="row gutters-tiny invisible" data-toggle="appear">
<div class="col-md-12">
<div class="block">
<div class="block-content">
<li class="list-group-item"><span class="badge badge-danger btn-xs">最新通知</span>  鑫迪建站分站系统V1.13最新版已发布</li>
<li class="list-group-item"><span class="badge badge-success btn-xs">程序下载</span>  <a href="http://www.78vu.cn/Jianzhan_API.zip" target="_blank">下载地址1</a> MD5（D43A118330333BC0BE54A83829633FA8）</li>
<li class="list-group-item"><span class="badge badge-info btn-xs">温馨提示</span>  搭建分站后，建站成本是根据您在鑫迪建站的代理级别算成本，如：高级代理1折建站，原价*1折=成本。</li>
<li class="list-group-item"><span class="badge badge-info btn-xs">重要信息</span>  鑫迪建站分站系统由官方发布，避免不了很多人进行二开后发布到网络。为了您的数据安全，请谨慎使用网络说流传的程序包，您可以通过MD5校检器辨别程序包的真伪。</li>
</div>
</div>
</div>
</div>
      <!--API公告 END-->
        <div class="block-content">
            <div class="row">
            	<!-- APIID -->
            	<div class="col-lg-4 col-md-6 col-sm-12">
            		<div class="codecard codecard-4 codecard-t4 codecard-notuse">
            			<div class="codecard-body">
            				<div class="code_title">
            					<p class="code_name">
            					<span class="glyphicon glyphicon-flag"></span>APIID
            					<span style="color:white;font-size:12px;"> 重要信息请妥善保管！</span>
            					</p>
            					<div class="code_func">
            					</div>
            				</div>
            				<div class="clearfix"></div>
            				<p class="code_p"><span class="code_line"><span class="code_show"><?php echo $apiinfo['apiid'];?></span></span></p>
            			</div>
            			<div class="codecard-bottom">
            				<div class="progress">
            					<div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            					</div>
            				</div>
            				<p class="code_expire">
            					<span class="fa fa-exclamation-circle"></span> APIID是用户API权限的唯一ID，不可更改
            				</p>
            			</div>
            		</div>
            	</div>
            	<!-- APIKEY -->
            	<div class="col-lg-4 col-md-6 col-sm-12">
            		<div class="codecard codecard-4 codecard-t4 codecard-notuse">
            			<div class="codecard-body">
            				<div class="code_title">
            					<p class="code_name">
            						<span class="glyphicon glyphicon-flag"></span> APIKEY
            						<span style="color:white;font-size:12px;"> 重要信息请妥善保管！</span>
            					</p>
            					<div class="code_func">
            					</div>
            				</div>
            				<div class="clearfix"></div>
            				<p class="code_p"><span class="code_line"><span class="code_show"><?php echo substr($apiinfo['apikey'],0,8);?></span><span class="code_hide"><?php echo substr($apiinfo['apikey'],8,32);?></span><span class="code_mask">************************</span></span></p>
            			</div>
            			<div class="codecard-bottom">
            				<div class="progress">
            					<div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            					</div>
            				</div>
            				<p class="code_expire">
            					<span class="fa fa-exclamation-circle"></span> APIKEY用于生成Token，相当于登录密码 <a href="javascript:;" onclick="Rekey();" class="code_funca code_renew tip_show" data-toggle="tooltip" data-placement="top" data-trigger="hover" data-title="只有在APIKEY泄露的情况下才有必要重置，重置后原有的APIKEY将会失效" data-instant="" data-original-title="" title=""><span class="code_func_text">重置</span></a>
            				</p>
            			</div>
            		</div>
            	</div>
            	<!-- Token -->
            	<?php
            	if($apiinfo['token']!='' || $apiinfo['token']!=NULL){
            	?>
            	<div class="col-lg-4 col-md-6 col-sm-12">
            		<div class="codecard codecard-4 codecard-t4 codecard-notuse">
            			<div class="codecard-body">
            				<div class="code_title">
            					<p class="code_name">
            						<span class="glyphicon glyphicon-flag"></span> Token
            						<span style="color:white;font-size:12px;"> 重要信息请妥善保管！</span>
            					</p>
            					<div class="code_func">
            					</div>
            				</div>
            				<div class="clearfix"></div>
            				<p class="code_p"><span class="code_line"><span class="code_show"><?php echo substr($apiinfo['token'],0,8);?></span><span class="code_hide">[为了安全起见，仅显示前8位]</span><span class="code_mask">************************</span></span></p>
            			</div>
            			<div class="codecard-bottom">
            				<div class="progress">
            					<div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            					</div>
            				</div>
            				<p class="code_expire">
            					<span class="fa fa-exclamation-circle"></span> Token用于获取每次请求的签名  <a href="javascript:;" onclick="ReToken();" class="code_funca code_renew tip_show" data-toggle="tooltip" data-placement="top" data-trigger="hover" data-title="清空Token是在Token泄露或重新安装时使用的，清空后会使现有的Token失效，请谨慎操作！" data-instant="" data-original-title="" title=""><span class="code_func_text">清空Token</span></a>
            				</p>
            			</div>
            		</div>
            	</div>
            	<?php
				}else{
				?>
				<div class="col-lg-4 col-md-6 col-sm-12">
            		<div class="codecard codecard-0 codecard-t4 codecard-notuse">
            			<div class="codecard-body">
            				<div class="code_title">
            					<p class="code_name">
            						<span class="glyphicon glyphicon-flag"></span> Token
            						<span style="color:white;font-size:12px;"> 重要信息请妥善保管！</span>
            					</p>
            					<div class="code_func">
            					</div>
            				</div>
            				<div class="clearfix"></div>
            				<p class="code_p">暂未生成Token</p>
            			</div>
            			<div class="codecard-bottom">
            				<div class="progress">
            					<div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            					</div>
            				</div>
            				<p class="code_expire">
            					<span class="fa fa-exclamation-circle"></span> 通过APIID和APIKEY请求鉴权才会生成Token
            				</p>
            			</div>
            		</div>
            	</div>
				<?php
				}
				?>
			</div>
        </div>
    </div>
</div>
</main>
<script src="assets/js/md5.js"></script>
<script>
	function Rekey(){
			var passwd=prompt("请输入密码进行确认！");
			if(passwd){
				window.location="api.php?act=Rekey&password="+md5(passwd);
			}else{
				
			}
	}
	
	function ReToken(){
			var passwd=prompt("请输入密码进行确认！");
			if(passwd){
				window.location="api.php?act=ReToken&password="+md5(passwd);
			}else{
				
			}
	}
</script>
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