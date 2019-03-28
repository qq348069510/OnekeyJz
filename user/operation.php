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
if(isset($_GET['act'])){
	if($_GET['act']=='reinstall'){
		if(!isset($_GET['site'])){
			exit('无站点');
		}
		$site=daddslashes($_GET['site']);
		$siteinfo = $DB->get_row("SELECT * FROM ".DB_PREFIX."_site WHERE domain='{$site}' LIMIT 1");
		if($siteinfo==''){
			exit("<script language='javascript'>alert('网站不存在，请检查各项参数是否正确');history.go(-1);</script>");
		}
		if ($siteinfo['user'] != $udata['id']){
			exit("<script language='javascript'>alert('权限不足，您无法管理其他用户的网站');history.go(-1);</script>");
		}
		$domain = $siteinfo['domain'];
		$password = $siteinfo['passwd'];
		$program_id = $siteinfo['program'];
		$program_id = $DB->get_row("SELECT * FROM ".DB_PREFIX."_program WHERE id='{$program_id}' LIMIT 1");
		$program=$program_id['install'];
		$epurl=readconfig('epurl');
		$ip=readconfig('epip');
		$ftp = new ftp($ip,21,$domain,$password);     // 打开FTP连接 
		$rows=$ftp->file_list('./wwwroot/');
		$url='';
		foreach($rows as $row){
			$url=$url.'&files[]=/wwwroot/'.$row;
		}
		$ftp->close();
		if($url!=''){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php?c=session&a=login');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('username' => $domain, 'passwd' => $password)));
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			$p = curl_exec($ch);
			curl_close($ch);
			preg_match('/PHPSESSID=(.{26})/i', $p, $matches);
			$cookie = $matches[1];

			// 首页
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $cookie));
			$p2 = curl_exec($ch);
			curl_close($ch);

			// FTP
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php?c=index&a=webftp');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $cookie));
			$p3=curl_exec($ch);
			curl_close($ch);

			// 删除
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://'.$epurl.'/vhost/index.php?c=webftp&a=rm'.$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: PHPSESSID=' . $cookie));
			$p4 = curl_exec($ch);
			curl_close($ch);
		}
		$ftp = new ftp($ip,21,$domain,$password);     // 打开FTP连接 
		$ftp->up_file('./install/onekey_reinstall.php','wwwroot/install.php');     // 上传文件 
		$ftp->close();
		$backdata = json_decode(file_get_contents('http://'.$domain.'.ae369.cn/install.php?program='.$program.'&domain='.$domain.'.af369.cn&user='.$domain.'&password='.$password),true);
		if ($backdata['code']!=1){
			exit("错误".$backdata['msg']);
		}else{
			exit("<script language='javascript'>alert('重新安装成功！');window.location.href='./site-list.php';</script>");
		}
	}
}else{
	exit('error');
}
?>