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

error_reporting(0);
header('Content-Type: text/html; charset=UTF-8');
define('IN_CRONLITE', true);
define('ROOT', dirname(__FILE__) . '/');
define('LOGIN_KEY', 'abcabc348069510');
define('ADMIN_KEY', 'cbacba954800800');
date_default_timezone_set("PRC");
$date = date("Y-m-d H:i:s");
session_start();

if (is_file(ROOT . '360safe/360webscan.php')) {//360网站卫士
    require_once(ROOT . '360safe/360webscan.php');
}
require ROOT . '../config.php';

if (!isset($port)) $port = '3306';
//连接数据库
include_once(ROOT . "db.class.php");
$DB = new DB($host, $user, $pwd, $dbname, $port);

$password_hash = '!@#%!s!';
include ROOT . "function.php";
include ROOT . "member.php";
include ROOT . "os.php";
include ROOT . "ftp.php";
include ROOT . "class.geetestlib.php";
?>