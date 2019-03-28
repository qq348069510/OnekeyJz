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
$site_num=$DB->count("SELECT count(*) FROM ".DB_PREFIX."_site WHERE user='".$udata['id']."';");
$program_num=$DB->count("SELECT count(*) FROM ".DB_PREFIX."_program WHERE 1;");
?>
<?php require './header.php';?>
<main id="main-container"><div class="content">
<div class="row gutters-tiny invisible" data-toggle="appear">
<div class="col-6 col-xl-3">
<a class="block block-link-shadow text-right" href="javascript:void(0)">
<div class="block-content block-content-full clearfix">
<div class="float-left mt-10 d-none d-sm-block">
<i class="si si-puzzle fa-3x text-body-bg-dark"></i>
</div>
<div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="<?php echo $program_num;?>">0</span>套</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">平台网站程序总数</div>
</div>
</a>
</div>
<div class="col-6 col-xl-3">
<a class="block block-link-shadow text-right" href="javascript:void(0)">
<div class="block-content block-content-full clearfix">
<div class="float-left mt-10 d-none d-sm-block">
<i class="si si-grid fa-3x text-body-bg-dark"></i>
</div>
<div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="<?php echo $site_num;?>">0</span>个</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">您所搭建网站数量</div>
</div>
</a>
</div>
<div class="col-6 col-xl-3">
<a class="block block-link-shadow text-right" href="javascript:void(0)">
<div class="block-content block-content-full clearfix">
<div class="float-left mt-10 d-none d-sm-block">
<i class="si si-envelope-open fa-3x text-body-bg-dark"></i>
</div>
<div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="<?php $homedate='1525928400'; echo round((time()-$homedate)/86400); ?>">0</span>天</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">平台已稳定运行</div>
</div>
</a>
</div>
<div class="col-6 col-xl-3">
<a class="block block-link-shadow text-right" href="javascript:void(0)">
<div class="block-content block-content-full clearfix">
<div class="float-left mt-10 d-none d-sm-block">
<i class="si si-users fa-3x text-body-bg-dark"></i>
</div>
<div class="font-size-h3 font-w600"><span data-toggle="countTo" data-speed="1000" data-to="85">0</span>％</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">平台开发进程</div>
</div>
</a>
</div>
</div>
<!--平台公告 START-->
<div class="row gutters-tiny invisible" data-toggle="appear">
<div class="col-md-12">
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">平台公告</h3>
</div>
<div class="block-content">
<?php echo readconfig('announcement');?>
</div>
</div>
</div>
</div>
<!--平台公告 END-->
<div class="row gutters-tiny invisible" data-toggle="appear">
<div class="col-md-6">
<div class="block">
<div class="block-header">
<h3 class="block-title">
本周 <small>数据统计</small>
</h3>
<div class="block-options">
<button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
<i class="si si-refresh"></i>
</button>
<button type="button" class="btn-block-option">
<i class="si si-wrench"></i>
</button>
</div>
</div>
<div class="block-content block-content-full">
<div class="pull-all">
<canvas id="myChart" class="js-chartjs-dashboard-lines"></canvas>
</div>
</div>
<div class="block-content">
<div class="row items-push">
<div class="col-6 col-sm-4 text-center text-sm-left">
<div class="font-size-sm font-w600 text-uppercase text-muted">这个星期</div>
<div class="font-size-h4 font-w600">50</div>
<div class="font-w600 text-success">
<i class="fa fa-caret-up"></i> +16%
</div>
</div>
<div class="col-6 col-sm-4 text-center text-sm-left">
<div class="font-size-sm font-w600 text-uppercase text-muted">This Week</div>
<div class="font-size-h4 font-w600">20</div>
<div class="font-w600 text-danger">
<i class="fa fa-caret-down"></i> -3%
</div>
</div>
<div class="col-12 col-sm-4 text-center text-sm-left">
<div class="font-size-sm font-w600 text-uppercase text-muted">Average</div>
<div class="font-size-h4 font-w600">1.3</div>
<div class="font-w600 text-success">
<i class="fa fa-caret-up"></i> +20%
</div>
</div>
</div>
</div>
</div>
</div>
<div class="col-md-6">
<div class="block">
<div class="block-header">
<h3 class="block-title">
Earnings <small>This week</small>
</h3>
<div class="block-options">
<button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
<i class="si si-refresh"></i>
</button>
<button type="button" class="btn-block-option">
<i class="si si-wrench"></i>
</button>
</div>
</div>
<div class="block-content block-content-full">
<div class="pull-all">
<canvas class="js-chartjs-dashboard-lines2"></canvas>
</div>
</div>
<div class="block-content bg-white">
<div class="row items-push">
<div class="col-6 col-sm-4 text-center text-sm-left">
<div class="font-size-sm font-w600 text-uppercase text-muted">This Month</div>
<div class="font-size-h4 font-w600">$ 6,540</div>
<div class="font-w600 text-success">
<i class="fa fa-caret-up"></i> +4%
</div>
</div>
<div class="col-6 col-sm-4 text-center text-sm-left">
<div class="font-size-sm font-w600 text-uppercase text-muted">This Week</div>
<div class="font-size-h4 font-w600">$ 1,525</div>
<div class="font-w600 text-danger">
<i class="fa fa-caret-down"></i> -7%
</div>
</div>
<div class="col-12 col-sm-4 text-center text-sm-left">
<div class="font-size-sm font-w600 text-uppercase text-muted">Balance</div>
<div class="font-size-h4 font-w600">$ 9,352</div>
<div class="font-w600 text-success">
<i class="fa fa-caret-up"></i> +35%
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="row gutters-tiny invisible" data-toggle="appear">
<div class="col-md-6">
<a class="block block-link-shadow overflow-hidden" href="javascript:void(0)">
<div class="block-content block-content-full">
<i class="si si-briefcase fa-2x text-body-bg-dark"></i>
<div class="row py-20">
<div class="col-6 text-right border-r">
<div class="invisible" data-toggle="appear" data-class="animated fadeInLeft">
<div class="font-size-h3 font-w600">16</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">Projects</div>
</div>
</div>
<div class="col-6">
<div class="invisible" data-toggle="appear" data-class="animated fadeInRight">
<div class="font-size-h3 font-w600">2</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">Active</div>
</div>
</div>
</div>
</div>
</a>
</div>
<div class="col-md-6">
<a class="block block-link-shadow overflow-hidden" href="javascript:void(0)">
<div class="block-content block-content-full">
<div class="text-right">
<i class="si si-users fa-2x text-body-bg-dark"></i>
</div>
<div class="row py-20">
<div class="col-6 text-right border-r">
<div class="invisible" data-toggle="appear" data-class="animated fadeInLeft">
<div class="font-size-h3 font-w600 text-info">63250</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">Accounts</div>
</div>
</div>
<div class="col-6">
<div class="invisible" data-toggle="appear" data-class="animated fadeInRight">
<div class="font-size-h3 font-w600 text-success">97%</div>
<div class="font-size-sm font-w600 text-uppercase text-muted">Active</div>
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
</footer></div><script src="assets/js/codebase-1.2.min.js"></script><script src="assets/js/plugins/chartjs/Chart.bundle.min.js"></script>
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
play('<?php echo $udata['name'];?>您好，欢迎使用<?php echo readconfig('title');?>，您可以在网站管理-搭建网站中搭建属于自己的网站');
</script>
<script>
var BePagesDashboard=function(){var a=function(){Chart.defaults.global.defaultFontColor="#555555",Chart.defaults.scale.gridLines.color="transparent",Chart.defaults.scale.gridLines.zeroLineColor="transparent",Chart.defaults.scale.display=!1,Chart.defaults.scale.ticks.beginAtZero=!0,Chart.defaults.global.elements.line.borderWidth=2,Chart.defaults.global.elements.point.radius=5,Chart.defaults.global.elements.point.hoverRadius=7,Chart.defaults.global.tooltips.cornerRadius=3,Chart.defaults.global.legend.display=!1;var a,e,r=jQuery(".js-chartjs-dashboard-lines"),o=jQuery(".js-chartjs-dashboard-lines2"),l={labels:["星期一","星期二","星期三","星期四","星期五","星期六","星期天"],datasets:[{label:"这个星期",fill:!0,backgroundColor:"rgba(66,165,245,.25)",borderColor:"rgba(66,165,245,1)",pointBackgroundColor:"rgba(66,165,245,1)",pointBorderColor:"#fff",pointHoverBackgroundColor:"#fff",pointHoverBorderColor:"rgba(66,165,245,1)",data:[10,15,13,1,22,30,11]}]},t={scales:{yAxes:[{ticks:{suggestedMax:50}}]},tooltips:{callbacks:{label:function(a,e){return" "+a.yLabel+" Sales"}}}},s={labels:["星期一","星期二","星期三","星期四","星期五","星期六","星期天"],datasets:[{label:"上个星期",fill:!0,backgroundColor:"rgba(156,204,101,.25)",borderColor:"rgba(156,204,101,1)",pointBackgroundColor:"rgba(156,204,101,1)",pointBorderColor:"#fff",pointHoverBackgroundColor:"#fff",pointHoverBorderColor:"rgba(156,204,101,1)",data:[190,219,235,320,360,354,390]}]},n={scales:{yAxes:[{ticks:{suggestedMax:480}}]},tooltips:{callbacks:{label:function(a,e){return" $ "+a.yLabel}}}};r.length&&(a=new Chart(r,{type:"line",data:l,options:t})),o.length&&(e=new Chart(o,{type:"line",data:s,options:n}))};return{init:function(){a()}}}();jQuery(function(){BePagesDashboard.init()});
</script></body>
</html>