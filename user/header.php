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

if(!defined('ROOT')) {exit('error!');} 
?>
<!doctype html>
<!--[if lte IE 9]>     <html lang="en" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en" class="no-focus"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<title>建站系统控制台</title>
<meta name="description" content="建站系统控制台">
<link rel="shortcut icon" href="./assets/img/favicons/favicon.png">
<link rel="icon" type="image/png" sizes="192x192" href="./assets/img/favicons/favicon-192x192.png">
<link rel="apple-touch-icon" sizes="180x180" href="./assets/img/favicons/apple-touch-icon-180x180.png">
<link rel="stylesheet" id="css-main" href="./assets/css/codebase-1.2.min.css">
<link rel="stylesheet" id="css-main" href="./assets/css/card.css">
<style>
	.geektestshow {
		display: block;
	}
	.geektesthide {
		display: none;
	}
	#geektestnotice {
		color: red;
	}
</style>
</head>
<body><div id="page-container" class="sidebar-o side-scroll page-header-modern main-content-boxed">
<nav id="sidebar">
<div id="sidebar-scroll">
<div class="sidebar-content">
<div class="content-header content-header-fullrow px-15">
<div class="content-header-section sidebar-mini-visible-b">
<span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
<span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
</span>
</div>
<div class="content-header-section text-center align-parent sidebar-mini-hidden">
<button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
<i class="fa fa-times text-danger"></i>
</button>
<div class="content-header-item">
<a class="link-effect font-w700" href="index.php">
<i class="si si-fire text-primary"></i>
<span class="font-size-xl text-dual-primary-dark">建站系统</span><span class="font-size-xl text-primary">控制台</span>
</a>
</div>
</div>
</div>
<div class="content-side content-side-full content-side-user px-10 align-parent">
<div class="sidebar-mini-visible-b align-v animated fadeIn">
<img class="img-avatar img-avatar32" src="./assets/img/avatars/avatar15.jpg" alt="">
</div>
<div class="sidebar-mini-hidden-b text-center">
<a class="img-link">
<img class="img-avatar" src="//q1.qlogo.cn/g?b=qq&s=100&nk=<?php echo $udata['qq'];?>" alt="<?php echo $udata['qq'];?>">
</a>
<ul class="list-inline mt-10">
<li class="list-inline-item">
<a class="link-effect text-dual-primary-dark font-size-xs font-w600 text-uppercase"><?php echo $udata['name'];?></a>
</li>
<li class="list-inline-item">
<a class="link-effect text-dual-primary-dark" data-toggle="layout" data-action="sidebar_style_inverse_toggle" href="javascript:void(0)">
<i class="si si-drop"></i>
</a>
</li>
<li class="list-inline-item">
<a class="link-effect text-dual-primary-dark" href="./login.php?logout">
<i class="si si-logout"></i>
</a>
</li>
</ul>
</div>
</div>
<div class="content-side content-side-full">
<ul class="nav-main">
<li>
<a href="index.php"><i class="si si-home"></i>首页</a></a>
</li>
<li>
<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-grid"></i><span class="sidebar-mini-hide">网站管理</span></a>
<ul>
<li>
<a href="site-list.php">网站列表</a>
<a href="site-add.php">搭建网站</a>
<a href="push-list.php">PUSH列表</a>
</li>
</ul>
</li>
<li>
<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-users"></i><span class="sidebar-mini-hide">账号管理</span></a>
<ul>
<li>
<a href="modify.php">修改资料</a>
</li>
<li>
<a href="ip-list.php">登录记录</a>
</li>
</ul>
</li>
<li>
<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-wallet"></i><span class="sidebar-mini-hide">我的钱包</span></a>
<ul>
<li>
<a href="balance.php">账户余额</a>
</li>
<li>
<a href="recharge.php">在线充值</a>
</li>
<?php
if($udata['daili']==0){
	echo '<li>
	';
	echo '<a href="daili.php">购买代理</a>';
	echo '</li>
	';
}else{
	$daili_level=$DB->get_row("select * from ".DB_PREFIX."_daili where id>".$udata['daili'].";");
	if($daili_level!=''){
		echo '<li>
		';
		echo '<a href="daili.php">升级代理</a>';
		echo '</li>
		';
	}
}
?>
<li>
<a href="recharge-history.php">充值记录</a>
</li>
</ul>
</li>
<li>
<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-key"></i><span class="sidebar-mini-hide">API 权限</span></a>
<ul>
<li>
<?php
if($apiinfo!=''){
	echo '<a href="api.php">API 管理</a>';
}else{
	echo '<a href="api-buy.php">权限开通</a>';
}
?>
</li>
</ul>
</li>
<li>
<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-share"></i><span class="sidebar-mini-hide">我的推广</span></a>
<ul>
<li>
<a href="recommend.php">推广链接</a>
</li>
<li>
<a href="recommend-list.php">推广列表</a>
</li>
</ul>
</li>
<li>
<a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-users"></i><span class="sidebar-mini-hide">联系客服</span></a>
<ul>
<li>
<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=9571564&site=qq&menu=yes" title="点此直接联系QQ">在线联系</a>
</li>
<li>
<a target="_blank" href="https://jq.qq.com/?_wv=1027&k=5FDueCJ" title="加入QQ群">加入QQ群</a>
</li>
</ul>
</li>
</ul>
<hr />
<center><strong>一键自助建站系统</strong></center>
</div>
</div>
</div>
</nav>
<header id="page-header">
<div class="content-header">
<div class="content-header-section">
<button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
<i class="fa fa-navicon"></i>
</button>
<div class="btn-group" role="group">
<button type="button" class="btn btn-circle btn-dual-secondary" id="page-header-options-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<i class="fa fa-wrench"></i>
</button>
<div class="dropdown-menu" aria-labelledby="page-header-options-dropdown">
<h6 class="dropdown-header">Header</h6>
<button type="button" class="btn btn-sm btn-block btn-alt-secondary" data-toggle="layout" data-action="header_fixed_toggle">Fixed Mode</button>
<button type="button" class="btn btn-sm btn-block btn-alt-secondary d-none d-lg-block mb-10" data-toggle="layout" data-action="header_style_classic">Classic Style</button>
<div class="d-none d-xl-block">
<h6 class="dropdown-header">Main Content</h6>
<button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10" data-toggle="layout" data-action="content_layout_toggle">Toggle Layout</button>
</div>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="be_layout_api.php">
<i class="si si-chemistry"></i> 接口API[未开发]
</a>
</div>
</div>
<div class="btn-group" role="group">
<button type="button" class="btn btn-circle btn-dual-secondary" id="page-header-themes-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<i class="fa fa-paint-brush"></i>
</button>
<div class="dropdown-menu min-width-150" aria-labelledby="page-header-themes-dropdown">
<h6 class="dropdown-header text-center">颜色主题</h6>
<div class="row no-gutters text-center mb-5">
<div class="col-4 mb-5">
<a class="text-default" data-toggle="theme" data-theme="default" href="javascript:void(0)">
<i class="fa fa-2x fa-circle"></i>
</a>
</div>
<div class="col-4 mb-5">
<a class="text-elegance" data-toggle="theme" data-theme="./assets/css/themes/elegance.min.css" href="javascript:void(0)">
<i class="fa fa-2x fa-circle"></i>
</a>
</div>
<div class="col-4 mb-5">
<a class="text-pulse" data-toggle="theme" data-theme="./assets/css/themes/pulse.min.css" href="javascript:void(0)">
<i class="fa fa-2x fa-circle"></i>
</a>
</div>
<div class="col-4 mb-5">
<a class="text-flat" data-toggle="theme" data-theme="./assets/css/themes/flat.min.css" href="javascript:void(0)">
<i class="fa fa-2x fa-circle"></i>
</a>
</div>
<div class="col-4 mb-5">
<a class="text-corporate" data-toggle="theme" data-theme="./assets/css/themes/corporate.min.css" href="javascript:void(0)">
<i class="fa fa-2x fa-circle"></i>
</a>
</div>
<div class="col-4 mb-5">
<a class="text-earth" data-toggle="theme" data-theme="./assets/css/themes/earth.min.css" href="javascript:void(0)">
<i class="fa fa-2x fa-circle"></i>
</a>
</div>
</div>
  <div class="dropdown-divider"></div>
<a class="dropdown-item" href="be_ui_color_themes.php">
<i class="fa fa-paint-brush"></i> 所有颜色主题样式
</a>
</div>
</div>
</div>
<div class="content-header-section">
<div class="btn-group" role="group">
<button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<?php echo $udata['name'];?><i class="fa fa-angle-down ml-5"></i>
</button>
<div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-user-dropdown">

<a class="dropdown-item">
<?php
$h=date('G');
if ($h<5) {
	$h='凌晨好，天还没亮呢';
}else if ($h>=5 && $h<8){
	$h='早上好，记得吃早餐哦';
}else if ($h>=8 && $h<11){
	$h='上午好，再忙也要喝水哦';
}else if ($h>=11 && $h<13){
	$h='中午好，该吃午饭了';
}else if ($h>=13 && $h<17){
	$h='下午好，适当放松一下';
}else if ($h>=17 && $h<22){
	$h='晚上好，累了就睡觉哦';
}else{
	$h='夜深了，熬夜可是不好的哦';
}
?>
<i class="si si-tag mr-5"></i> <?php echo $h;?>！
</a>

<a class="dropdown-item d-flex align-items-center justify-content-between" href="balance.php">
<span><i class="si si-wallet mr-5"></i> 当前余额</span>
<span class="badge badge-primary">￥<?php echo $udata['balance'];?></span>
</a>

<div class="dropdown-divider"></div>
<a class="dropdown-item" href="./login.php?logout">
<i class="si si-logout mr-5"></i> 退出登录
</a>
</div>
</div>
<button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
<i class="fa fa-tasks"></i>
</button>
</div>
</div>
<div id="page-header-loader" class="overlay-header bg-primary">
<div class="content-header content-header-fullrow text-center">
<div class="content-header-item">
<i class="fa fa-sun-o fa-spin text-white"></i>
</div>
</div>
</div>
</header>