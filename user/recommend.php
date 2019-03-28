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
if(readconfig('extension')!=0){}else exit("推广功能已关闭，无法使用！");
?>
<?php require './header.php';?>
<main id="main-container">
<div class="content">
    <h2 class="content-heading">推广链接</h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">链接生成</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-8">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="val-phoneus">推广链接 <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" onclick="this.select()" value="http://<?php echo $_SERVER['HTTP_HOST'];?>/user/reg.php?id=<?php echo $udata['id'];?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="val-phoneus">返利比例 <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" value="<?php echo readconfig('extension');?>%" readonly>
                            </div>
                        </div>
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