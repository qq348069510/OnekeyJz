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

include('../include/common.php');
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<!doctype html>
<!--[if lte IE 9]>     <html lang="zh-cn" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="zh-cn" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>域名管理</title>
        <meta name="description" content="域名管理">
        <link rel="stylesheet" id="css-main" href="./assets/css/codebase.min-2.1.css">
    </head>
<?php if(isset($_GET['sid'])){
	$sid=daddslashes($_GET['sid']);
	if ($sid==''){
        sysmsg('无效请求','本次请求没有使用正确的参数');
        exit();
    }
	$siteinfo = $DB->get_row("SELECT * FROM ".DB_PREFIX."_site WHERE sid='{$sid}' LIMIT 1");
    if ($siteinfo['user'] != $udata['id']){
        sysmsg('权限不足','您无法管理其他用户的网站');
        exit();
    }else{
        $domainlist = $siteinfo['optdomain'];
		if(!$domainlist){
			$domainlist = array();
		}else{
			if(strstr($domainlist,",")){
				$domainlist = explode(',',$domainlist);
			}else{
				$domainlist = array($siteinfo['optdomain']);
			}
		}
    }
	if(isset($_POST['add'])){
		$domain=daddslashes($_POST['domain']);
		if(strpos($domain,readconfig('domain'))!==false){
			sysmsg('该域名禁止绑定','您无权添加系统默认域名，如有疑问请联系管理员',4,true);
			exit();
		}else if(strpos($domain,readconfig('epip'))!==false){
			sysmsg('禁止绑定服务器IP','服务器IP禁止用户绑定，如有疑问请联系管理员',4,true);
			exit();
		}
		$domain_list = explode("\n", str_replace(array(" ", ",", "|"), "\n", readconfig('optdomain')));
		foreach ($domain_list as $optdomain){
			if(strpos($domain,$optdomain)!==false && $domain!=$siteinfo['domain'].'.'.$optdomain){
				sysmsg('该域名禁止绑定','您无法添加'.$optdomain.'域名除['.$siteinfo['domain'].']外的其他前缀',4,true);
				exit();
			}
		}
		$epurl = readconfig('epurl');
		$username = $siteinfo['domain'];
		$password = $siteinfo['passwd'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php?c=session&a=login');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('username' => $username, 'passwd' => $password)));
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		$p = curl_exec($ch);
		curl_close($ch);
		preg_match('/PHPSESSID=(.{26})/i', $p, $matches);
		$cookie = $matches[1];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $cookie));
		$p2 = curl_exec($ch);
		curl_close($ch);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php?c=domain&a=add&domain=' . urlencode($domain) . '&subdir=%2Fwwwroot');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $cookie));
		$p3 = curl_exec($ch);
		curl_close($ch);
		if($p3 == "成功"){
			array_push($domainlist,$domain);
			$num = count($domainlist);
			$domainstr = $siteinfo['optdomain'];
			if($num == 1){
				$domainstr = $domainlist[0];
				$DB->query("UPDATE `".DB_PREFIX."_site` SET `optdomain` = '{$domainstr}' WHERE `sid` = '{$sid}';");
			}else{
				$domainstr = $domainstr.','.$domain;
				$DB->query("UPDATE `".DB_PREFIX."_site` SET `optdomain` = '{$domainstr}' WHERE `sid` = '{$sid}';");
			}
			$siteinfo['optdomain']=$domain;
		}else{
			sysmsg('绑定失败','该域名已被绑定或系统错误，请检查绑定域名，如是系统错误请联系管理员',4,true);
			exit();
		}
	}else if(isset($_GET['type'])){
		if(daddslashes($_GET['type'])=='del'){
			$domain=daddslashes($_GET['domain']);
			if(strpos($siteinfo['optdomain'],$domain)!==false){
				$epurl = readconfig('epurl');
				$username = $siteinfo['domain'];
				$password = $siteinfo['passwd'];
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php?c=session&a=login');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('username' => $username, 'passwd' => $password)));
				curl_setopt($ch, CURLOPT_HEADER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				$p = curl_exec($ch);
				curl_close($ch);
				preg_match('/PHPSESSID=(.{26})/i', $p, $matches);
				$cookie = $matches[1];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $cookie));
				$p2 = curl_exec($ch);
				curl_close($ch);
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php?c=domain&a=del');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('domain' => $domain)));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $cookie));
				$p3 = curl_exec($ch);
				curl_close($ch);
				if($p3 == "成功"){
					$num = count($domainlist);
					$domainstr = $siteinfo['optdomain'];
					if($num == 1){
						$DB->query("UPDATE `".DB_PREFIX."_site` SET `optdomain` = '' WHERE `sid` = '{$sid}';");
					}else{
						$domainstr = str_replace(','.$domain,'',$domainstr);
						$DB->query("UPDATE `".DB_PREFIX."_site` SET `optdomain` = '{$domainstr}' WHERE `sid` = '{$sid}';");
					}
					exit("<script language='javascript'>alert('删除成功');window.location.href='domain.php?sid=$sid';</script>");
				}else{
					sysmsg('删除失败','该域名可能未绑定，请仔细检查',4,true);
					exit();
				}
			}else{
				sysmsg('删除失败','该域名可能未绑定，请仔细检查',4,true);
				exit();
			}
		}else{
			sysmsg('无效请求','本次请求没有使用正确的参数');
			exit();
		}
	}
	
?>
<main id="main-container" style="min-height: 493px;"><div class="content">
<div class="block">
<div class="block-header block-header-default">
<h3 class="block-title">域名管理</h3>
<div class="block-options">
<button type="button" class="btn-block-option">
<i class="si si-wrench"></i>
</button>
</div>
</div>
<div class="block-content">
<div class="row justify-content-center py-20">
<div class="col-xl-9">
	<div class="alert alert-success" role="alert">
		<?php if(readconfig('optdomain')!='')echo '您可绑定的默认域名有：'.readconfig('optdomain').'</br>'; ?>绑定自己的域名需要将域名CNAME解析到<?php echo readconfig('domain');?> 或者A记录解析到<?php echo readconfig('epip');?> 
		<a href="http://fqa.78vu.cn/628093" target="_blank">查看帮助</a>
	</div>
    <div class="panel-body">
       <form  method="post" action="?sid=<?=$sid?>" class="js-validation-bootstrap">
       	<div class="form-group row">
<label class="col-lg-4 col-form-label" for="val-username">添加域名 <span class="text-danger">*</span></label>
<div class="col-lg-6">
<input type="text" class="form-control" name="domain" placeholder="请输入要绑定的域名" required />
</div>
</div>
<div class="form-group row">
<div class="col-lg-8 ml-auto">
<button type="submit" name="add" id="embed-submit" class="btn btn-alt-primary">添加</button>
</div>
</div>
     </form>
    </div>
<table class="table" >
        <thead>
          <tr>
            <th>域名</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
		  <tr>
		  	<?php if($siteinfo['optdomain']!=''){
		  		foreach($domainlist as $d){ ?>
            <td><a href="http://<?=$d?>" target="_blank"><?=$d?></a></td>
            <td><a class="btn btn-xs btn-danger" href="?sid=<?=$sid?>&type=del&domain=<?=$d?>">删除</a></td>
          </tr>
		  <?php 
				}
			}else{?>
		  	<td>暂无绑定域名</td>
		  <?php }?>
        </tbody>
      </table>
</div>
</div>
</div>
</div>
</main>
</body>
</html>
<?php
}else{
	sysmsg('无效请求','本次请求没有使用正确的参数');
	exit();
}
?>