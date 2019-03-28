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
$people=$DB->count("SELECT count(*) FROM ".DB_PREFIX."_user WHERE recommend='".$udata['id']."';");
$commission=$DB->count("SELECT sum(commission) FROM ".DB_PREFIX."_extension WHERE recommend='".$udata['id']."';");
?>
<?php require './header.php';?>
<main id="main-container"><div class="content">
<div class="row gutters-tiny invisible" data-toggle="appear">
<div class="col-md-6">
<a class="block block-link-shadow overflow-hidden" href="javascript:void(0)">
<div class="block-content block-content-full">
<i class="si si-wallet fa-2x text-body-bg-dark"></i>
<div class="row py-20">
<div class="col-6 text-right border-r">
<div class="invisible" data-toggle="appear" data-class="animated fadeInLeft">
<div class="font-size-h3 font-w600"><?php echo $udata['balance']?>元</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">余额</div>
</div>
</div>
<div class="col-6">
<div class="invisible" data-toggle="appear" data-class="animated fadeInRight">
<div class="font-size-h3 font-w600"><?php echo $udata['freeze_balance']?>元</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">冻结</div>
</div>
</div>
</div>
</div>
</a>
</div>
<div class="col-md-6">
<a class="block block-link-shadow overflow-hidden" href="javascript:void(0)">
<div class="block-content block-content-full">

<i class="si si-users fa-2x text-body-bg-dark"></i>
<div class="row py-20">
<div class="col-6 text-right border-r">
<div class="invisible" data-toggle="appear" data-class="animated fadeInLeft">
<div class="font-size-h3 font-w600 text-info"><?php echo $people; ?>人</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">推广人数</div>
</div>
</div>
<div class="col-6">
<div class="invisible" data-toggle="appear" data-class="animated fadeInRight">
<div class="font-size-h3 font-w600 text-success"><?php echo $commission!=''?$commission:'0'; ?>元</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">推广返利</div>
</div>
</div>
</div>
</div>
</a>
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
</body>
</html>