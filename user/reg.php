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

if(isset($_POST['submit']) && isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
	include("../include/common.php");
	//极验验证
	$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
	$data = array(
			"user_id" => 'reg', # 网站用户id
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
	}//处理
	if($geetest){
		if(!isset($_POST['signup-terms']))exit("<script language='javascript'>alert('请您同意条款和条件！');history.go(-1);</script>");
		$username=daddslashes($_POST['signup-username']);
		$name=daddslashes($_POST['signup-name']);
		$qq=daddslashes($_POST['signup-qq']);
		$email=daddslashes($_POST['signup-email']);
		$password=daddslashes($_POST['signup-password']);
		$recommend=daddslashes($_POST['signup-recommend']);
		$password_confirm=daddslashes($_POST['signup-password-confirm']);
		$row=$DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE username='{$username}' or qq='{$qq}' limit 1");
		if($row!='')exit("<script language='javascript'>alert('该用户或QQ已被注册！');history.go(-1);</script>");
		if(!is_numeric($recommend)){
			$recommend=0;
		}else{
			$row=$DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE id='{$recommend}' limit 1");
			if($row=='')$recommend=0;
		}
		if($password!=$password_confirm)exit("<script language='javascript'>alert('两次密码不同！');history.go(-1);</script>");
		$sql="insert into `".DB_PREFIX."_user` (`username`,`password`,`qq`,`email`,`name`,`regdate`,`recommend`,`active`) values ('".$username."','".md5($password)."','".$qq."','".$email."','".$name."','".$date."','".$recommend."',1)";
		if($DB->query($sql)){
			exit("<script language='javascript'>alert('注册成功，请登录！');window.location.href='./login.php';</script>");
		}else{
			exit("<script language='javascript'>alert('未知错误，请联系管理员！');history.go(-1);</script>");
		}
	}
}
?>
<!doctype html>
<!--[if lte IE 9]>     <html lang="zh-cn" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="zh-cn" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>建站系统-注册账号</title>
        <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
        <link rel="stylesheet" id="css-main" href="assets/css/codebase.min-2.1.css">
        <style>
        	.show {
        		display: block;
        		}
        	.hide {
        		display: none;
        		}
        	#notice {
        		color: red;
        		}
        </style>
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
                <form class="js-validation-signup" action="" method="post">
                    <div class="block block-themed block-rounded block-shadow">
                        <div class="block-header bg-gd-emerald">
                            <h3 class="block-title">请填写注册信息</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option">
                                    <i class="si si-wrench"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="signup-username">用户名</label>
                                    <input type="text" class="form-control" id="signup-username" name="signup-username" placeholder="用户名至少包含3个字符">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="signup-name">昵称</label>
                                    <input type="text" class="form-control" id="signup-name" name="signup-name" placeholder="小爱">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="signup-qq">QQ</label>
                                    <input type="text" class="form-control" id="signup-qq" name="signup-qq" placeholder="123456789">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="signup-email">邮箱</label>
                                    <input type="email" class="form-control" id="signup-email" name="signup-email" placeholder="admin@example.com">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="signup-password">密码</label>
                                    <input type="password" class="form-control" id="signup-password" name="signup-password" placeholder="密码至少包含5个字符">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="signup-password-confirm">再次输入密码</label>
                                    <input type="password" class="form-control" id="signup-password-confirm" name="signup-password-confirm" placeholder="********">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="signup-recommend">推荐人</label>
                                    <input type="text" class="form-control" name="signup-recommend" placeholder="无推荐人可不填" <?php echo isset($_GET['id'])?'value="'.htmlspecialchars($_GET['id']).'" readonly':'';?>>
                                </div>
                            </div>
                            <div class="form-group row">
                            	<div class="col-12" id="embed-captcha">
                            		<div id="wait" class="show">正在加载验证码......</div>
                            		<label id="notice" class="hide">请先完成验证</label>
                            	</div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-sm-6 push">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="signup-terms" name="signup-terms">
                                        <label class="custom-control-label" for="signup-terms"><a href="#" data-toggle="modal" data-target="#modal-terms">我已阅读条款和条件</a></label>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-right push">
                                    <button type="submit" id="embed-submit" name="submit" class="btn btn-alt-success">
                                        <i class="fa fa-user-plus mr-10"></i> 立即注册
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="block-content bg-body-light">
                            <div class="form-group text-center">
                            	<!-- 弹框
                                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="#" data-toggle="modal" data-target="#modal-terms">
                                    <i class="fa fa-book text-muted mr-5"></i> 阅读条款和条件
                                </a>-->
                                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="login.php">
                                    <i class="fa fa-user text-muted mr-5"></i> 登录
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
<div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-slidedown" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">条款和条件 </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" id="TermsPausePlay" onclick="TermsPlay()" title="语音朗读">
                            <i id="Terms-Play" class="si si-volume-2"></i>
                        </button>
                      <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
<?php include("../include/common.php"); echo readconfig('terms');?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" onclick="PausePlay()" data-dismiss="modal">关闭</button>
                <a onclick="TermsChecked();PausePlay()"><button type="button" class="btn btn-alt-success" data-dismiss="modal">
                    <i class="fa fa-check"></i> 阅读完毕
                </button></a>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/codebase.min-2.1.js"></script><script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/js/gt.js"></script>
<script>
var audio=document.createElement('audio');  
var play = function (s) {
    var URL = 'https://fanyi.baidu.com/gettts?lan=zh&text=' + encodeURIComponent(s) + '&spd=5&source=web'

    if(!audio){
        audio.controls = false  
        audio.src = URL 
        document.body.appendChild(audio)  
    }
    audio.src = URL  
    audio.play();
}
function TermsPlay(){
	play('条款和条件内容如下：<?php echo readconfig('terms');?>');
	document.getElementById("Terms-Play").className="si si-volume-off";
}
function PausePlay(){
	audio.pause();
}
</script>
<script>
    var handlerEmbed = function (captchaObj) {
        $("#embed-submit").click(function (e) {
            var validate = captchaObj.getValidate();
            if (!validate) {
                $("#notice")[0].className = "show";
              play('请先完成验证');
                setTimeout(function () {
                    $("#notice")[0].className = "hide";
                }, 2000);
                e.preventDefault();
            }
        });
        // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
        captchaObj.appendTo("#embed-captcha");
        captchaObj.onReady(function () {
            $("#wait")[0].className = "hide";
        });
        // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
    };
    $.ajax({
        // 获取id，challenge，success（是否启用failback）
        url: "../GeektestCaptcha.php?act=reg&t=" + (new Date()).getTime(), // 加随机数防止缓存
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
<script type="text/javascript">
function TermsChecked(){
	document.getElementById("signup-terms").checked=true;
}
</script>
<script src="assets/js/pages/op_auth_signup.js"></script><br />
</body>
</html>