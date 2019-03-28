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
<h2 class="content-heading">充值记录</h2>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">记录列表</h3>
</div>
<div class="block-content"><div class="table-responsive">
<table class="table table-striped table-vcenter">
<thead>
<tr>
<th class="text-center" style="width: 100px;"><i class="si si-calendar"></i> 充值时间</th>
<th>充值信息</th>
<th>支付状态</th>
</tr>
</thead>
<tbody>
<?php
$rs=$DB->query("select * from ".DB_PREFIX."_recharge where user='{$udata['id']}' order by id desc;");
if($udata['per']==1){
	$rs=$DB->query("select * from ".DB_PREFIX."_recharge order by id desc;");
}
while($row = $DB->fetch($rs))
{
if($row['status']=='1'){
	$row['status']='<button type="button" class="btn btn-sm btn-alt-success" data-type="success" >支付成功</button>';
}else{
	$row['status']='<a href="recharge-submit.php?id='.$row['id'].'" ><button type="button" class="btn btn-sm btn-alt-danger" data-type="danger">立即支付</button></a>';
}
?>
<tr>
<td class="text-center font-w600""><?php echo $row['date'];?></td>
<td class="font-w600">在<?php echo $row['addres'];?>使用<?php echo pay_type($row['pay_type']);?>充值<?php echo $row['money'];?>RMB</td>
<td class="font-w600"><?php echo $row['status'];?></td>
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
<?php echo readconfig('copyright');?></div>
</div>
</footer></div><script src="assets/js/codebase-1.2.min.js"></script><script>jQuery(function(){Codebase.helpers('table-tools');});</script>
</body>
</html>