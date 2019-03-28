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
<main id="main-container" style="min-height: 543px;"><div class="content">
<h2 class="content-heading">登录记录</h2>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">记录列表</h3>
</div>
<div class="block-content"><div class="table-responsive">
<table class="table table-striped table-vcenter">
<thead>
<tr>
<th class="text-center" style="width: 100px;"><i class="si si-user"></i></th>
<th>登录记录信息</th>
</tr>
</thead>
<tbody>
<?php
$rs=$DB->query("select * from ".DB_PREFIX."_ip where user='{$udata['id']}' order by id desc;");
while($row = $DB->fetch($rs)){
if($row['ip']==''){
	$row['ip']='未知';
}
?>
<tr>
<td class="text-center"><img class="img-avatar img-avatar48" src="//q1.qlogo.cn/g?b=qq&s=100&nk=<?php echo $udata['qq'];?>" alt="<?php echo $adminname['qq'];?>"></td>
<td class="font-w600">账户于<?php echo $row['date'];?>在<?php echo $row['addres'];?>使用<?php echo $row['platform'];?>设备登录，登录IP为：<?php echo $row['ip'];?></td>
</tr>
<?php
}
?>
</tbody>
</table>
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
</footer></div><script src="assets/js/codebase-1.2.min.js"></script><script>jQuery(function(){Codebase.helpers('table-tools');});</script>
</body>
</html>