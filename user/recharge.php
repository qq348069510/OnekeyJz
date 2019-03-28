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
?>
<?php require './header.php';?>
<main id="main-container">
<div class="content">
    <h2 class="content-heading">充值中心</h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">在线充值</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-8">
                    <form class="js-validation-bootstrap" action="recharge-submit.php" method="post" target="_blank">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >支付方式 <span class="text-danger">*</span></label>
                            <div class="col-lg-4">
                                <br><input type="radio" checked="checked" value="alipay" name="pay_type"><img src="./assets/images/alipay.gif" width="135" height="45" alt="支付宝在线支付" title="支付宝在线支付">
                                <br><input type="radio"  value="wxpay" name="pay_type"><img src="./assets/images/wxpay.gif" width="142" height="45" alt="微信在线支付" title="微信在线支付">
                                <br><input type="radio"  value="qpay" name="pay_type"><img src="./assets/images/qpay.gif" width="142" height="45" alt="QQ钱包在线支付" title="QQ钱包在线支付">
                            
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >当前余额 <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" value="<?php echo $udata['balance']?>" readonly >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >充值金额 <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="money" placeholder="每笔限最高充值100元" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" >验证码 <span class="text-danger">*</span></label>
                            <div class="col-lg-6" id="embed-captcha">
                                <div id="wait" class="geektestshow">正在加载验证码......</div>
                                <label id="geektestnotice" class="geektesthide">请先完成验证</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-8 ml-auto">
                                <button type="submit" name="recharge" id="embed-submit" class="btn btn-alt-primary">立即充值</button>
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