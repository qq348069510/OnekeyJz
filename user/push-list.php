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
<main id="main-container" style="min-height: 493px;"><div class="content">
<h2 class="content-heading">PUSH列表</h2>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">PUSH管理</h3>
</div>
<div class="block-content">
<div class="table-responsive">
<table class="table table-striped table-vcenter">
<thead>
<tr>
<th>类型</th>
<th>对方用户名</th>
<th>网站域名</th>
<th>PUSH时间</th>
<th>PUSH方式</th>
<th>索要金额</th>
<th style="width: 30%;">状态</th>
<th>操作</th>
</tr>
</thead>
<tbody>
<?php
$rs=$DB->query("select * from ".DB_PREFIX."_push where new_user=".$udata['id']." or old_user=".$udata['id']." order by id;");
while($row = $DB->fetch($rs))
{
	$site_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_site WHERE sid='".$row['site_id']."' limit 1");
	
	if($row['old_user'] == $udata['id']){ ///发起方
		$type = '发起push';
		$user_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE id='".$row['new_user']."' limit 1");
		if($row['active'] == 1){
			$active="<p style='color:blue'>已发起PUSH请求，等待接收</p>";
		}else if($row['active'] == 2){
			$active="<p style='color:green'>对方已接收</p>";
		}else if($row['active'] == 3){
			$active="<p style='color:red'>对方已拒收</p>";
		}else if($row['active'] == 4){
			$active="<p style='color:red'>已撤销PUSH请求</p>";
		}else{
			$active="<p style='color:red'>未知状态，请联系管理员</p>";
		}
	}else if($row['new_user'] == $udata['id']){ //接收方
		$type = '接收push';
		$user_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE id='".$row['old_user']."' limit 1");
		if($row['active'] == 1){
			$active="<p style='color:blue'>已收到PUSH请求，等待接收</p>";
		}else if($row['active'] == 2){
			$active="<p style='color:green'>已接收</p>";
		}else if($row['active'] == 3){
			$active="<p style='color:red'>已拒收</p>";
		}else if($row['active'] == 4){
			$active="<p style='color:red'>对方已撤销PUSH请求</p>";
		}else{
			$active="<p style='color:red'>未知状态，请联系管理员</p>";
		}
	}else{ //判断错误
		$user_row = array();
		$user_row['username'] = '未知错误';
		$type = '未知操作';
		$active="<p style='color:red'>未知状态，请联系管理员</p>";
	}
	
	if($row['type'] == 1){
		$row['type'] = '普通PUSH';
		$row['price'] = '无价格';
	}else if($row['type'] == 2){
		$row['type'] = '带价PUSH';
	}else if($row['type'] == 3){
		$row['type'] = '极速PUSH';
		$row['price'] = '无价格';
	}else{
		$row['type'] = '未知类型';
		$row['price'] = '无价格';
	}
?>
<tr>
<td class="font-w600"><?php echo $type;?></td>
<td class="font-w600"><?php echo $user_row['username'];?></td>
<td class="font-w600"><a href="http://<?php echo $site_row['domain'];?>.<?php echo readconfig('domain');?>/" target="_blank"><?php echo $site_row['domain'];?>.<?php echo readconfig('domain');?></a></td>
<td class="font-w600"><?php echo $row['date'];?></td>
<td class="font-w600"><?php echo $row['type'];?></td>
<td class="font-w600"><?php echo $row['price'];?></td>
<td class="font-w600"><?php echo $active;?></td>
<?php
if($row['old_user'] == $udata['id'] && $row['active'] == 1){
?>
<td class="font-w600"><a class="btn btn-danger js-click-ripple-enabled" data-toggle="click-ripple" onclick="Undo(<?php echo $row['id'];?>,'<?php echo $site_row['domain'];?>')">撤销</a></td>
<?php
}else if($row['new_user'] == $udata['id'] && $row['active'] == 1){
	if($row['type'] == '带价PUSH'){
?>
<td class="font-w600"><a class="btn btn-success js-click-ripple-enabled" data-toggle="click-ripple" onclick="Receive_pay(<?php echo $row['id'];?>,'<?php echo $site_row['domain'];?>','<?php echo $row['price'];?>')">接收</a> <a class="btn btn-danger js-click-ripple-enabled" data-toggle="click-ripple" onclick="Refused(<?php echo $row['id'];?>,'<?php echo $site_row['domain'];?>')">拒收</a></td>
<?php
	}else{
?>
<td class="font-w600"><a class="btn btn-success js-click-ripple-enabled" data-toggle="click-ripple" onclick="Receive(<?php echo $row['id'];?>,'<?php echo $site_row['domain'];?>')">接收</a> <a class="btn btn-danger js-click-ripple-enabled" data-toggle="click-ripple" onclick="Refused(<?php echo $row['id'];?>,'<?php echo $site_row['domain'];?>')">拒收</a></td>
<?php
	}
?>
<?php
}else{
?>
<td class="font-w600">无需操作</td>
<?php
}
?>
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
<script src="assets/layer/layer.js"></script>
<script>
	function Receive(id, site) {
		layer.confirm('确定要接收[' + site + '.<?php echo readconfig('domain');?>]吗？', {
			title:'确定接收？' ,
			btn: ['确定','取消']
		}, function(){
			layer.closeAll('dialog'); 
			layer.prompt({
				formType: 1,
				title: '请输入密码确认',
			}, function(value, index, elem){
				window.location = "push.php?type=receive&id=" + id + "&site=" + site + "&pwd=" + value;
				layer.close(index);
			});
		}, function(){
		layer.msg('操作已取消');
		})
	}
	function Receive_pay(id, site, price) {
		layer.confirm('确定要接收[' + site + '.<?php echo readconfig('domain');?>]<br/>并支付' + price +'元吗？', {
			title:'确定接收？' ,
			btn: ['确定并支付','取消']
		}, function(){
			layer.closeAll('dialog'); 
			layer.prompt({
				formType: 1,
				title: '请输入密码确认',
			}, function(value, index, elem){
				window.location = "push.php?type=receive&id=" + id + "&site=" + site + "&pwd=" + value;
				layer.close(index);
			});
		}, function(){
		layer.msg('操作已取消');
		})
	}
	function Refused(id, site) {
		layer.confirm('确定要拒收[' + site + '.<?php echo readconfig('domain');?>]吗？', {
			title:'确定拒收？' ,
			btn: ['确定','取消']
		}, function(){
			layer.closeAll('dialog'); 
			layer.prompt({
				formType: 1,
				title: '请输入密码确认',
			}, function(value, index, elem){
				window.location = "push.php?type=refused&id=" + id + "&site=" + site + "&pwd=" + value;
				layer.close(index);
			});
		}, function(){
		layer.msg('操作已取消');
		})
	}
	function Undo(id, site) {
		layer.confirm('确定要撤销对[' + site + '.<?php echo readconfig('domain');?>]的PUSH吗？', {
			title:'确定撤销？' ,
			btn: ['确定','取消']
		}, function(){
			layer.closeAll('dialog'); 
			layer.prompt({
				formType: 1,
				title: '请输入密码确认',
			}, function(value, index, elem){
				window.location = "push.php?type=undo&id=" + id + "&site=" + site + "&pwd=" + value;
				layer.close(index);
			});
		}, function(){
		layer.msg('操作已取消');
		})
	}
</script>
</body>
</html>