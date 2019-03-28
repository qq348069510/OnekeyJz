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

if(isset($_GET['program']) && isset($_GET['user']) && isset($_GET['password'])){
	$domain=$_SERVER['HTTP_HOST'];
	$user=addslashes($_GET['user']);
	$password=addslashes($_GET['password']);
	$program=addslashes($_GET['program']);
	@$copy=copy('http://onekeyprogram.78vu.cn/'.$program.'.zip', 'onkey_install.zip');
	if($copy){
	$zip=new ZipArchive();
      if($zip->open('onkey_install.zip')===true){
      $zip->extractTo('./');
      $zip->close();
	}
	//访问unzip 执行解压，等预处理操作
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://'.$domain.'/unzip.php');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	curl_close($ch);
	//替换数据库信息
	$conn = file_get_contents(dirname(__FILE__).'/config.php');
	$conn = str_replace('sjkuser',$user,$conn);
	$conn = str_replace('sjkname',$user,$conn);
	$conn = str_replace('sjkpwd',$password,$conn);
	file_put_contents(dirname(__FILE__).'/config.php',$conn);
	//链接数据库，无sql文件则不链接数据库
	if(is_file(dirname(__FILE__).'/install.sql')){
	$mysqli = new mysqli('localhost',$user,$password,$user);
	$Sqls = file_get_contents(dirname(__FILE__).'/install.sql');
	$mysqli->query("set names utf8");
	$Sqls=substr($Sqls,0,strlen($Sqls)-1);
	$SqlArr = explode(';', $Sqls);
	foreach ( $SqlArr as $sql ) {
		$mysqli->query($sql);
	}
	}
	if(is_file(dirname(__FILE__).'/onkey_install.zip'))unlink(dirname(__FILE__).'/onkey_install.zip');//删除安装包
	if(is_file(dirname(__FILE__).'/01.zip'))unlink(dirname(__FILE__).'/01.zip');//删除程序包
	if(is_file(dirname(__FILE__).'/install.sql'))unlink(dirname(__FILE__).'/install.sql');//删除SQL文件
	if(is_file(dirname(__FILE__).'/unzip.php'))unlink(dirname(__FILE__).'/unzip.php');//删除自解压文件
	if(is_file(dirname(__FILE__).'/install.php'))unlink(dirname(__FILE__).'/install.php');//自我删除
	$msg=array('code'=>'1','msg'=>'ok');
}else{$msg=array('code'=>'0','msg'=>'未找到该程序安装文件，请联系管理员');}
}else{
	$msg=array('code'=>'0','msg'=>'无参数');
}
$json=json_encode($msg);
exit($json);

?>