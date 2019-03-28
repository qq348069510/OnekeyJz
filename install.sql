# 玩客建站系统数据库语句文件
DROP TABLE IF EXISTS `onekey_api`;
CREATE TABLE `onekey_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `apiid` varchar(255) DEFAULT NULL,
  `apikey` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `onekey_api` DISABLE KEYS */;
/*!40000 ALTER TABLE `onekey_api` ENABLE KEYS */;

DROP TABLE IF EXISTS `onekey_config`;
CREATE TABLE `onekey_config` (
  `k` varchar(255) NOT NULL DEFAULT '',
  `v` text,
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置';

/*!40000 ALTER TABLE `onekey_config` DISABLE KEYS */;
INSERT INTO `onekey_config` VALUES ('addgg','添加域名时的公告'),('announcement','<div>\r\n<li class=\"list-group-item\">\r\n\r\n\r\n\r\n\r\n<a href=\"./site-add.php\" class=\"btn btn-primary btn-sm\">搭建网站</a>\r\n\r\n<a href=\"./site-list.php\" class=\"btn btn-success btn-sm\">网站列表</a>\r\n\r\n<a href=\"./daili.php\" class=\"btn btn-info btn-sm\">购买代理</a>\r\n\r\n</li>\r\n\r\n\r\n\r\n\r\n<li class=\"list-group-item\"><span class=\"badge badge-danger btn-xs\">市场监管</span>&nbsp;玩客建站系统内所有网站禁止低价出售（如原价3元，对外出售禁止低于3元！）</li>\r\n\r\n\r\n\r\n<li class=\"list-group-item\"><span class=\"badge badge-success btn-xs\">代理折扣</span>&nbsp; 初级代理8折搭建，中级代理5折搭建，高级代理0元搭建。 心动不如行动。  <a href=\"./daili.php\">购买代理</a></li>\r\n\r\n<li class=\"list-group-item\"><span class=\"badge badge-warning btn-xs\">4</span>&nbsp; 玩客建站-全网首家支持无限次重装的建站</li>\r\n<p></p>\r\n</div>'),('apiprice','API购买价格'),('authcode','easypane后台的安全码'),('copyright','<a class=\"font-w600\" href=\"/\" target=\"_blank\">玩客自助建站系统 Beta 1.1</a> &copy; <span>2018</span>'),('domain','默认赠送域名'),('epip','easypane的IP'),('epurl','easypane的lP:3312'),('extension','推荐返利比例 百分比单位 0为关闭'),('optdomain','赠送二级域名的一级域名 多个域名英文逗号分隔'),('terms','<p>1.内容等待添加，点击阅读完毕即可</p>'),('title','玩客建站系统');
/*!40000 ALTER TABLE `onekey_config` ENABLE KEYS */;

DROP TABLE IF EXISTS `onekey_daili`;
CREATE TABLE `onekey_daili` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '代理名称',
  `money` float(16,2) DEFAULT NULL COMMENT '购买代理价格',
  `discount` int(3) DEFAULT NULL COMMENT '代理折扣（百分比数）',
  `action` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='代理级别';

/*!40000 ALTER TABLE `onekey_daili` DISABLE KEYS */;
INSERT INTO `onekey_daili` VALUES (1,'初级代理',6.00,80,0),(2,'中级代理',8.00,50,0),(3,'高级代理',10.00,3,1);
/*!40000 ALTER TABLE `onekey_daili` ENABLE KEYS */;

DROP TABLE IF EXISTS `onekey_extension`;
CREATE TABLE `onekey_extension` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recommend` int(11) DEFAULT NULL COMMENT '推荐人',
  `user` int(11) DEFAULT NULL COMMENT '充值用户',
  `money` float(16,2) DEFAULT NULL COMMENT '充值金额',
  `commission` float(16,2) DEFAULT NULL COMMENT '返利金额',
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推广列表';

/*!40000 ALTER TABLE `onekey_extension` DISABLE KEYS */;
/*!40000 ALTER TABLE `onekey_extension` ENABLE KEYS */;

DROP TABLE IF EXISTS `onekey_ip`;
CREATE TABLE `onekey_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `admin` int(11) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `addres` varchar(30) DEFAULT NULL,
  `platform` varchar(150) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `onekey_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `onekey_ip` ENABLE KEYS */;

DROP TABLE IF EXISTS `onekey_program`;
CREATE TABLE `onekey_program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '程序名称',
  `install` varchar(255) DEFAULT NULL COMMENT '安装标识',
  `price` float(16,2) DEFAULT NULL COMMENT '程序价格',
  `daili_price` int(1) DEFAULT '1' COMMENT '是否参与代理价格优惠',
  `product_id` int(5) DEFAULT '1' COMMENT 'easypanel的虚拟主机产品ID，默认1',
  `api` int(1) NOT NULL DEFAULT '1',
  `active` int(1) DEFAULT '1' COMMENT '是否上架',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `onekey_program` DISABLE KEYS */;
INSERT INTO `onekey_program` VALUES (1,'代刷网','daishua',80.00,0,1,0,0),(2,'导航网-款式1','daohangwang',0.00,1,1,1,1),(3,'轻论坛','qingluntan',3.00,1,1,1,1),(4,'表白网','niyan',3.00,1,1,1,1),(5,'Emlog','emlog',3.00,1,1,1,1),(6,'ZBlog','zblog',3.00,1,1,1,1),(7,'要饭网','yaofan',0.00,1,1,1,1),(8,'同学录','tongxuelu',3.00,1,1,1,1),(9,'代挂网-款式1','dengjidaigua',3.00,1,1,1,1),(10,'发卡网','faka',3.00,1,1,1,1);
/*!40000 ALTER TABLE `onekey_program` ENABLE KEYS */;

DROP TABLE IF EXISTS `onekey_push`;
CREATE TABLE `onekey_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `old_user` int(11) NOT NULL,
  `new_user` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `price` float(16,2) NOT NULL DEFAULT '0.00',
  `date` datetime NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站PUSH';

/*!40000 ALTER TABLE `onekey_push` DISABLE KEYS */;
/*!40000 ALTER TABLE `onekey_push` ENABLE KEYS */;

DROP TABLE IF EXISTS `onekey_recharge`;
CREATE TABLE `onekey_recharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `pay_type` varchar(100) DEFAULT '',
  `money` float(16,2) DEFAULT NULL,
  `addres` varchar(30) DEFAULT NULL,
  `order_no` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='在线充值';

/*!40000 ALTER TABLE `onekey_recharge` DISABLE KEYS */;
/*!40000 ALTER TABLE `onekey_recharge` ENABLE KEYS */;

DROP TABLE IF EXISTS `onekey_site`;
CREATE TABLE `onekey_site` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(16) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `optdomain` varchar(1024) DEFAULT NULL,
  `passwd` varchar(16) NOT NULL,
  `program` int(11) DEFAULT NULL,
  `price` float(16,2) DEFAULT NULL,
  `expire_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `del` int(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `onekey_site` DISABLE KEYS */;
/*!40000 ALTER TABLE `onekey_site` ENABLE KEYS */;

DROP TABLE IF EXISTS `onekey_user`;
CREATE TABLE `onekey_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT '',
  `qq` varchar(255) DEFAULT '',
  `balance` float(16,2) DEFAULT '0.00',
  `freeze_balance` float(16,2) DEFAULT '0.00' COMMENT '冻结金额',
  `regdate` datetime DEFAULT NULL,
  `recommend` int(11) DEFAULT '0' COMMENT '推荐人',
  `recommend_balance` float(16,2) NOT NULL DEFAULT '0.00' COMMENT '推荐返利余额',
  `daili` int(11) DEFAULT '0' COMMENT '代理 0为非代理',
  `daili_expire_time` datetime DEFAULT NULL COMMENT '代理到期时间，0000-00-00 00:00:00为非代理',
  `gift_api` int(1) NOT NULL DEFAULT '0' COMMENT '是否赠送API权限 1为赠送',
  `active` int(11) NOT NULL DEFAULT '1' COMMENT '状态 0为冻结',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='平台用户';

/*!40000 ALTER TABLE `onekey_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `onekey_user` ENABLE KEYS */;