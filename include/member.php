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

if(!defined('IN_CRONLITE'))exit();

if(isset($_COOKIE["islogin"])){ //Cookies用户登录方式
	if($_COOKIE["console_user"]){
		$console_user=base64_decode($_COOKIE['console_user']);
		$udata = $DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE username='$console_user' limit 1");
		if(!$udata){
			setcookie("islogin", "", time() - 604800);
			setcookie("console_user", "", time() - 604800);
			setcookie("console_pass", "", time() - 604800);
		}
		$console_pass=sha1($udata['password'].LOGIN_KEY);
		if($console_pass==$_COOKIE["console_pass"]){
			$islogin=1;
			if($udata['active']==0){
				setcookie("islogin", "", time() - 604800);
				setcookie("console_user", "", time() - 604800);
				setcookie("console_pass", "", time() - 604800);
				exit('您的账号已被冻结，请联系管理员或更换其他账号');
			}
			$apiinfo=$DB->get_row("SELECT * FROM ".DB_PREFIX."_api WHERE user='".$udata['id']."' limit 1");
		}else{
			setcookie("islogin", "", time() - 604800);
			setcookie("console_user", "", time() - 604800);
			setcookie("console_pass", "", time() - 604800);
		}
	}
}
if(isset($_SESSION['islogin'])){ //session用户登录方式
	if($_SESSION["console_user"]){
		$console_user=base64_decode($_SESSION['console_user']);
		$udata = $DB->get_row("SELECT * FROM ".DB_PREFIX."_user WHERE username='$console_user' limit 1");
		if(!$udata){
			unset($_SESSION['islogin']);
			unset($_SESSION['console_user']);
			unset($_SESSION['console_pass']);
		}
		$console_pass=sha1($udata['password'].LOGIN_KEY);
		if($console_pass==$_SESSION["console_pass"]){
			$islogin=1;
			if($udata['active']==0){
				unset($_SESSION['islogin']);
				unset($_SESSION['console_user']);
				unset($_SESSION['console_pass']);
				exit('您的账号已被冻结，请联系管理员或更换其他账号');
			}
			$apiinfo=$DB->get_row("SELECT * FROM ".DB_PREFIX."_api WHERE user='".$udata['id']."' limit 1");
		}else{
			unset($_SESSION['islogin']);
			unset($_SESSION['console_user']);
			unset($_SESSION['console_pass']);
		}
	}
}
?>