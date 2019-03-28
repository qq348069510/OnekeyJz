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
<h2 class="content-heading">网站列表</h2>
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">网站管理</h3>
</div>
<div class="block-content">
<div class="table-responsive">
<table class="table table-striped table-vcenter">
<thead>
<tr>
<th>默认域名</th>
<th>域名管理</th>
<th>网站程序</th>
<th style="width: 30%;">状态</th>
<th>操作</th>
</tr>
</thead>
<tbody>
<?php
$rs=$DB->query("select * from ".DB_PREFIX."_site where user=".$udata['id']." order by sid;");
while($row = $DB->fetch($rs))
{
	$program_row = $DB->get_row("SELECT * FROM ".DB_PREFIX."_program WHERE id='".$row['program']."' limit 1");
	if($row['active']){
		$row['active']="<p style='color:green'>正常</p>";
	}else{
		$row['active']="<p style='color:red'>已停封</p>";
	}																		
?>
<tr>
<td class="font-w600"><a href="http://<?php echo $row['domain'];?>.<?php echo readconfig('domain');?>/" target="_blank"><?php echo $row['domain'];?>.<?php echo readconfig('domain');?></a></td>
<td class="font-w600"><a class="btn btn-primary js-click-ripple-enabled" data-toggle="click-ripple" onclick="domaingl(<?=$row['sid']?>);">域名管理</a></td>
<td class="font-w600"><?php echo $program_row['name'];?></td>
<td class="font-w600"><?php echo $row['active'];?></td>
<td class="font-w600"><a class="btn btn-warning js-click-ripple-enabled" data-toggle="click-ripple" onclick="Reinstall('<?php echo $row['domain'];?>')">重装</a> <a class="btn btn-success js-click-ripple-enabled" data-toggle="click-ripple" onclick="Renewals(<?php echo $row['sid'];?>)">续费</a> <a class="btn btn-success js-click-ripple-enabled" data-toggle="click-ripple" href="./push.php?site_id=<?php echo $row['sid']?>">PUSH</a></td>
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
	function domaingl(id){
		layer.open({
		  type: 2,
		  title: '域名管理',
		  offset: '100px',
		  shadeClose: true,
		  shade: false,
		  maxmin: true,
		  area: ['80%', '75%'],
		  content: 'domain.php?sid='+id
		});
	}
</script>
<script type="text/javascript">
	function Reinstall(site){
			var domain=prompt("此操作不可逆，数据无法恢复，确定要重新安装吗?\n\n请验证默认域名执行重新安装");
			if(domain==site+'.<?php echo readconfig('domain');?>'){
				window.location="operation.php?act=reinstall&site="+site;
			}else if(domain===null){
				
			}else{
				alert("域名输入错误！");
			}
	}
	
	function Renewals(id){
		var year_error=true;
		while(year_error){
			var year=prompt("请输入续费时长，单位为年","1");
			if(!isNaN(year) && year!=null){
				var year_error=false;
				var renewals=confirm("确定要续费"+year+'年？');
				if(renewals==true){
					window.location="operation.php?act=renewals&id="+id;
				}
			}else if(year==null){
				var year_error=false;
			}else{
				var year_error=confirm("请输入正确时长！");
			}
		}
}
</script>
</body>
</html>