/*
MySQL Backup
Source Server Version: 5.7.13
Source Database: shop0211
Date: 2017-04-07 17:34:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `s11_admin`
-- ----------------------------
DROP TABLE IF EXISTS `s11_admin`;
CREATE TABLE `s11_admin` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员';

-- ----------------------------
--  Table structure for `s11_admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `s11_admin_role`;
CREATE TABLE `s11_admin_role` (
  `admin_id` mediumint(8) unsigned NOT NULL COMMENT '管理员id',
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色id',
  KEY `admin_id` (`admin_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员角色[中间表]';

-- ----------------------------
--  Table structure for `s11_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `s11_attribute`;
CREATE TABLE `s11_attribute` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `attr_name` varchar(30) NOT NULL COMMENT '属性名称',
  `attr_type` enum('唯一','可选') NOT NULL COMMENT '属性类型',
  `attr_option_values` varchar(300) NOT NULL DEFAULT '' COMMENT '属性可选值',
  `type_id` mediumint(8) unsigned NOT NULL COMMENT '所属类型id',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='属性';

-- ----------------------------
--  Table structure for `s11_brand`
-- ----------------------------
DROP TABLE IF EXISTS `s11_brand`;
CREATE TABLE `s11_brand` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `brand_name` varchar(30) NOT NULL COMMENT '品牌名称',
  `site_url` varchar(150) NOT NULL DEFAULT '' COMMENT '官方网址',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '品牌Logo图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='品牌';

-- ----------------------------
--  Table structure for `s11_cart`
-- ----------------------------
DROP TABLE IF EXISTS `s11_cart`;
CREATE TABLE `s11_cart` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  `goods_attr_id` varchar(150) NOT NULL DEFAULT '' COMMENT '商品属性id',
  `goods_number` mediumint(8) unsigned NOT NULL COMMENT '商品数量',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员id',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';

-- ----------------------------
--  Table structure for `s11_category`
-- ----------------------------
DROP TABLE IF EXISTS `s11_category`;
CREATE TABLE `s11_category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类的id,0:顶级分类',
  `cat_name` varchar(30) NOT NULL COMMENT '分类名称',
  `is_floor` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否推荐到楼层',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='分类';

-- ----------------------------
--  Table structure for `s11_comment`
-- ----------------------------
DROP TABLE IF EXISTS `s11_comment`;
CREATE TABLE `s11_comment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  `content` varchar(200) NOT NULL COMMENT '内容',
  `addtime` datetime NOT NULL COMMENT '发表时间',
  `star` tinyint(3) unsigned NOT NULL COMMENT '分值',
  `click_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '有用',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='评论';

-- ----------------------------
--  Table structure for `s11_comment_reply`
-- ----------------------------
DROP TABLE IF EXISTS `s11_comment_reply`;
CREATE TABLE `s11_comment_reply` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员id',
  `comment_id` mediumint(8) unsigned NOT NULL COMMENT '评论id',
  `content` varchar(200) NOT NULL COMMENT '内容',
  `addtime` datetime NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='评论回复';

-- ----------------------------
--  Table structure for `s11_goods`
-- ----------------------------
DROP TABLE IF EXISTS `s11_goods`;
CREATE TABLE `s11_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `goods_name` varchar(150) NOT NULL COMMENT '商品名称',
  `market_price` decimal(10,2) NOT NULL COMMENT '市场价格',
  `shop_price` decimal(10,2) NOT NULL COMMENT '本店价格',
  `goods_desc` longtext COMMENT '商品描述',
  `is_on_sale` enum('是','否') NOT NULL DEFAULT '是' COMMENT '是否上架',
  `is_delete` enum('是','否') NOT NULL DEFAULT '是' COMMENT '是否放在回收站',
  `addtime` datetime NOT NULL DEFAULT '1000-01-01 00:00:00' COMMENT '添加时间',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '原图',
  `sm_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '小图',
  `mid_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '中图',
  `big_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '大图',
  `mbig_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '更大图',
  `edittime` datetime DEFAULT NULL COMMENT '修改时间',
  `brand_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '品牌id',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类id',
  `type_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '类型id',
  `promote_price` decimal(10,2) NOT NULL COMMENT '促销价格',
  `promote_start_date` datetime NOT NULL COMMENT '促销开始时间',
  `promote_end_date` datetime NOT NULL COMMENT '促销结束时间',
  `is_new` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否新品',
  `is_hot` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否热卖',
  `is_best` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否精品',
  `sort_num` tinyint(3) unsigned NOT NULL COMMENT '排序权重',
  `is_floor` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否推荐到楼层',
  `is_updated` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否被修改',
  PRIMARY KEY (`id`),
  KEY `shop_price` (`shop_price`),
  KEY `addtime` (`addtime`),
  KEY `is_on_sale` (`is_on_sale`),
  KEY `brand_id` (`brand_id`),
  KEY `cat_id` (`cat_id`),
  KEY `promote_price` (`promote_price`),
  KEY `promote_start_date` (`promote_start_date`),
  KEY `promote_end_date` (`promote_end_date`),
  KEY `is_new` (`is_new`),
  KEY `is_hot` (`is_hot`),
  KEY `is_best` (`is_best`),
  KEY `sort_num` (`sort_num`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='商品';

-- ----------------------------
--  Table structure for `s11_goods_attr`
-- ----------------------------
DROP TABLE IF EXISTS `s11_goods_attr`;
CREATE TABLE `s11_goods_attr` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `attr_value` varchar(150) NOT NULL DEFAULT '' COMMENT '属性值',
  `attr_id` mediumint(8) unsigned NOT NULL COMMENT '属性id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  PRIMARY KEY (`id`),
  KEY `attr_id` (`attr_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COMMENT='商品属性';

-- ----------------------------
--  Table structure for `s11_goods_cat`
-- ----------------------------
DROP TABLE IF EXISTS `s11_goods_cat`;
CREATE TABLE `s11_goods_cat` (
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  `cat_id` mediumint(8) unsigned NOT NULL COMMENT '分类id',
  KEY `goods_id` (`goods_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品扩展分类';

-- ----------------------------
--  Table structure for `s11_goods_number`
-- ----------------------------
DROP TABLE IF EXISTS `s11_goods_number`;
CREATE TABLE `s11_goods_number` (
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  `goods_number` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `goods_attr_id` varchar(150) NOT NULL COMMENT '商品属性表的id,如果是多个,就用程序拼成字符串存到这个字段',
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='库存';

-- ----------------------------
--  Table structure for `s11_goods_pic`
-- ----------------------------
DROP TABLE IF EXISTS `s11_goods_pic`;
CREATE TABLE `s11_goods_pic` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `pic` varchar(150) NOT NULL COMMENT '原图',
  `big_pic` varchar(150) NOT NULL COMMENT '大图',
  `mid_pic` varchar(150) NOT NULL COMMENT '中图',
  `sm_pic` varchar(150) NOT NULL COMMENT '小图',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COMMENT='商品相册';

-- ----------------------------
--  Table structure for `s11_member`
-- ----------------------------
DROP TABLE IF EXISTS `s11_member`;
CREATE TABLE `s11_member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `face` varchar(150) NOT NULL DEFAULT '' COMMENT '头像',
  `jifen` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员';

-- ----------------------------
--  Table structure for `s11_member_level`
-- ----------------------------
DROP TABLE IF EXISTS `s11_member_level`;
CREATE TABLE `s11_member_level` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `level_name` varchar(30) NOT NULL COMMENT '级别名称',
  `jifen_bottom` mediumint(8) unsigned NOT NULL COMMENT '积分下限',
  `jifen_top` mediumint(8) unsigned NOT NULL COMMENT '积分上限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='会员级别';

-- ----------------------------
--  Table structure for `s11_member_price`
-- ----------------------------
DROP TABLE IF EXISTS `s11_member_price`;
CREATE TABLE `s11_member_price` (
  `level_id` mediumint(8) unsigned NOT NULL COMMENT '级别id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  `price` decimal(10,2) NOT NULL COMMENT '会员价格',
  KEY `level_id` (`level_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员价格';

-- ----------------------------
--  Table structure for `s11_order`
-- ----------------------------
DROP TABLE IF EXISTS `s11_order`;
CREATE TABLE `s11_order` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员id',
  `addtime` int(10) unsigned NOT NULL COMMENT '添加时间',
  `pay_status` enum('是','否') NOT NULL DEFAULT '否' COMMENT '支付状态',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `total_price` decimal(10,2) NOT NULL COMMENT '订单总价',
  `shr_name` varchar(30) NOT NULL COMMENT '收货人姓名',
  `shr_tel` varchar(30) NOT NULL COMMENT '收货人电话',
  `shr_province` varchar(30) NOT NULL COMMENT '收货人省份',
  `shr_city` varchar(30) NOT NULL COMMENT '收货人城市',
  `shr_area` varchar(30) NOT NULL COMMENT '收货人地区',
  `shr_address` varchar(30) NOT NULL COMMENT '收货人详细地址',
  `post_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发货状态,0:未发货,1:已发货,2:已收货',
  `post_number` varchar(30) NOT NULL DEFAULT '' COMMENT '快递单号',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `addtime` (`addtime`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='订单基本信息';

-- ----------------------------
--  Table structure for `s11_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `s11_order_goods`;
CREATE TABLE `s11_order_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_id` mediumint(8) unsigned NOT NULL COMMENT '订单id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  `goods_attr_id` varchar(150) NOT NULL DEFAULT '' COMMENT '商品属性id',
  `goods_number` mediumint(8) unsigned NOT NULL COMMENT '商品数量',
  `price` decimal(10,2) NOT NULL COMMENT '购买价格',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='订单商品表';

-- ----------------------------
--  Table structure for `s11_privilege`
-- ----------------------------
DROP TABLE IF EXISTS `s11_privilege`;
CREATE TABLE `s11_privilege` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级权限id',
  `pri_name` varchar(30) NOT NULL COMMENT '权限名称',
  `module_name` varchar(30) NOT NULL DEFAULT '' COMMENT '模块名称',
  `controller_name` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action_name` varchar(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='权限';

-- ----------------------------
--  Table structure for `s11_role`
-- ----------------------------
DROP TABLE IF EXISTS `s11_role`;
CREATE TABLE `s11_role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_name` varchar(30) NOT NULL COMMENT '角色名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
--  Table structure for `s11_role_pri`
-- ----------------------------
DROP TABLE IF EXISTS `s11_role_pri`;
CREATE TABLE `s11_role_pri` (
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '权限id',
  `pri_id` mediumint(8) unsigned NOT NULL COMMENT '权限id',
  KEY `role_id` (`role_id`),
  KEY `pri_id` (`pri_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限[中间表]';

-- ----------------------------
--  Table structure for `s11_sphinx_id`
-- ----------------------------
DROP TABLE IF EXISTS `s11_sphinx_id`;
CREATE TABLE `s11_sphinx_id` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '现有索引最后一件商品ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='sphinx';

-- ----------------------------
--  Table structure for `s11_type`
-- ----------------------------
DROP TABLE IF EXISTS `s11_type`;
CREATE TABLE `s11_type` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type_name` varchar(30) NOT NULL COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='类型';

-- ----------------------------
--  Table structure for `s11_yinxiang`
-- ----------------------------
DROP TABLE IF EXISTS `s11_yinxiang`;
CREATE TABLE `s11_yinxiang` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品id',
  `yx_name` varchar(30) NOT NULL COMMENT '印象名称',
  `yx_count` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '印象次数',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='印象';

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `s11_admin` VALUES ('1','root','63a9f0ea7bb98050796b649e85481845'), ('2','Jack','4ff9fc6e4e5d5f590c4f2134a8cc96d1');
INSERT INTO `s11_admin_role` VALUES ('2','2'), ('2','5');
INSERT INTO `s11_attribute` VALUES ('1','颜色','可选','金色,银色,白色,黑色','3'), ('3','季节','可选','夏季,冬季,春秋季','4'), ('4','材质','唯一','','4'), ('5','出厂日期','唯一','','3'), ('6','屏幕尺寸','唯一','','3'), ('7','像素','可选','100,500,1200','3'), ('8','尺码','可选','M,L,XL,XXL','4');
INSERT INTO `s11_brand` VALUES ('1','meizu','meizu.com','Brand/2017-02-18/58a81d1785173.png'), ('3','xiaomi','xiaomi.com','Brand/2017-02-18/58a81d9dca390.png');
INSERT INTO `s11_category` VALUES ('1','0','家用电器','是'), ('2','0','手机、数码、京东通信','是'), ('3','0','电脑、办公','否'), ('4','0','家居、家具、家装、厨具','否'), ('5','0','男装、女装、内衣、珠宝','否'), ('6','0','个护化妆','否'), ('8','0','运动户外','否'), ('9','0','汽车、汽车用品','否'), ('10','0','母婴、玩具乐器','否'), ('11','0','食品、酒类、生鲜、特产','否'), ('12','0','营养保健','否'), ('13','0','图书、音像、电子书','否'), ('14','0','彩票、旅行、充值、票务','否'), ('15','0','理财、众筹、白条、保险','否'), ('16','1','大家电','是'), ('17','1','生活电器','是'), ('18','1','厨房电器','否'), ('19','0','个护健康','否'), ('20','1','五金家装','是'), ('21','2','iphone','是'), ('22','16','冰箱','否'), ('23','3','鼠标','否'), ('24','8','山地车','否'), ('25','12','海产品','否'), ('26','5','正装','否'), ('27','26','商务办公','否');
INSERT INTO `s11_comment` VALUES ('1','1','2','苟富贵','2017-04-01 12:08:15','4','0'), ('2','1','2','找出v型出v','2017-04-01 12:08:36','5','0'), ('3','1','2','士大夫似的','2017-04-01 12:13:23','5','0'), ('4','1','2','的发的','2017-04-01 12:13:27','2','0'), ('5','1','2','啊人托人','2017-04-01 12:13:32','1','0'), ('6','1','2','得得得','2017-04-01 12:13:38','2','0'), ('7','1','2','尔特让他','2017-04-01 12:45:34','5','0'), ('8','1','2','出v型不','2017-04-01 15:22:51','3','0'), ('9','1','2','玩儿','2017-04-01 16:25:08','5','0'), ('10','1','2','阿士大夫fcs','2017-04-01 16:53:44','1','0'), ('11','1','2','jkjaljf','2017-04-01 17:11:44','5','0'), ('12','1','2','打发打发','2017-04-01 17:12:03','2','0'), ('13','1','2','请问请问','2017-04-01 22:59:00','3','0'), ('14','1','2','v表现出v吧','2017-04-01 23:41:31','5','0');
INSERT INTO `s11_comment_reply` VALUES ('1','1','13','二玩儿','2017-04-01 23:17:44'), ('2','1','13','是短发','2017-04-01 23:24:12'), ('3','1','13','风格化','2017-04-01 23:24:46'), ('4','1','13','速度','2017-04-01 23:31:23'), ('5','1','13','速度','2017-04-01 23:31:25'), ('6','1','12','打发打发','2017-04-01 23:32:36'), ('7','1','12','其味无穷','2017-04-01 23:36:07'), ('8','1','12','其味无穷','2017-04-01 23:36:15'), ('9','1','12','是短发','2017-04-01 23:37:08'), ('10','1','9','大声道','2017-04-01 23:40:02'), ('11','1','9','啊实打实的','2017-04-01 23:40:09'), ('12','1','10','阿萨德','2017-04-01 23:40:46'), ('13','1','10','风格是梵蒂冈','2017-04-01 23:40:50');
INSERT INTO `s11_goods` VALUES ('1','小米手机','1999.00','20000.00','<p><span style=\"color:#ff0000;\">手机手机手机</span></p><p><span style=\"color:#ff0000;\"></span></p><p><img src=\"http://www.0211.com/Public/umeditor/php/upload/20170322/14901569917603.jpeg\" alt=\"14901569917603.jpeg\" /></p><p><img src=\"http://www.0211.com/Public/umeditor/php/upload/20170322/14901569962540.png\" alt=\"14901569962540.png\" /></p><p><span style=\"color:#ff0000;\"><br /></span><br /></p>','是','是','2017-03-17 10:28:26','Goods/2017-03-17/58cb49ca53811.jpg','Goods/2017-03-17/thumb_3_58cb49ca53811.jpg','Goods/2017-03-17/thumb_2_58cb49ca53811.jpg','Goods/2017-03-17/thumb_1_58cb49ca53811.jpg','Goods/2017-03-17/thumb_0_58cb49ca53811.jpg','2017-03-28 17:17:38','3','2','3','100.00','2017-03-17 10:28:09','2017-03-23 00:00:00','是','是','是','30','是','0'), ('2','电视','2999.00','2888.00','','是','是','2017-03-17 10:35:13','Goods/2017-03-17/58cb4b60c721f.png','Goods/2017-03-17/thumb_3_58cb4b60c721f.png','Goods/2017-03-17/thumb_2_58cb4b60c721f.png','Goods/2017-03-17/thumb_1_58cb4b60c721f.png','Goods/2017-03-17/thumb_0_58cb4b60c721f.png','2017-03-22 18:48:13','1','17','0','2699.00','2017-03-17 10:33:58','2017-03-18 00:00:00','是','否','否','40','是','0'), ('3','测试楼层品牌','100.00','99.00','','是','是','2017-03-17 22:37:56','Goods/2017-03-17/58cbf4c3bdd09.jpeg','Goods/2017-03-17/thumb_3_58cbf4c3bdd09.jpeg','Goods/2017-03-17/thumb_2_58cbf4c3bdd09.jpeg','Goods/2017-03-17/thumb_1_58cbf4c3bdd09.jpeg','Goods/2017-03-17/thumb_0_58cbf4c3bdd09.jpeg','2017-03-18 18:48:56','1','20','0','1.00','2017-03-17 22:36:09','2017-03-19 00:00:00','是','否','否','100','是','0'), ('4','没蓝E','1100.00','1000.00','','是','是','2017-03-28 17:13:28','Goods/2017-03-28/58da2938b5e49.jpg','Goods/2017-03-28/thumb_3_58da2938b5e49.jpg','Goods/2017-03-28/thumb_2_58da2938b5e49.jpg','Goods/2017-03-28/thumb_1_58da2938b5e49.jpg','Goods/2017-03-28/thumb_0_58da2938b5e49.jpg','2017-03-29 23:30:20','1','2','0','0.00','2017-03-28 17:12:27','2017-03-28 17:12:27','是','否','否','100','是','0'), ('5','华为手机','0.00','0.00','','是','是','2017-04-04 22:58:34','','','','','',NULL,'0','2','0','0.00','2017-04-04 22:57:55','2017-04-04 22:57:55','否','否','否','100','否','0'), ('6','海尔','0.00','0.00','','是','是','2017-04-04 23:47:50','','','','','',NULL,'0','16','0','0.00','2017-04-04 23:47:41','2017-04-04 23:47:41','否','否','否','100','否','0'), ('7','宝马','0.00','0.00','','是','是','2017-04-05 00:13:24','','','','','',NULL,'0','22','0','0.00','2017-04-05 00:13:13','2017-04-05 00:13:13','否','否','否','100','否','0'), ('8','青岛','0.00','0.00','','是','是','2017-04-05 00:16:24','','','','','',NULL,'0','17','0','0.00','2017-04-05 00:16:09','2017-04-05 00:16:09','否','否','否','100','否','0'), ('9','海信','0.00','0.00','','是','是','2017-04-05 00:22:36','','','','','',NULL,'0','16','0','0.00','2017-04-05 00:22:23','2017-04-05 00:22:23','否','否','否','100','否','0'), ('10','戴尔','0.00','0.00','','是','是','2017-04-05 00:28:11','','','','','',NULL,'0','20','0','0.00','2017-04-05 00:27:49','2017-04-05 00:27:49','否','否','否','100','否','0'), ('11','中国','0.00','0.00','','是','是','2017-04-05 00:29:15','','','','','',NULL,'0','22','0','0.00','2017-04-05 00:29:04','2017-04-05 00:29:04','否','否','否','100','否','0'), ('12','美的','0.00','0.00','','是','是','2017-04-05 00:35:43','','','','','',NULL,'0','16','0','0.00','2017-04-05 00:35:36','2017-04-05 00:35:36','否','否','否','100','否','0'), ('13','鼠标','0.00','0.00','','是','是','2017-04-05 00:40:09','','','','','',NULL,'0','16','0','0.00','2017-04-05 00:39:49','2017-04-05 00:39:49','否','否','否','100','否','0'), ('14','金鱼','0.00','0.00','','是','是','2017-04-05 00:42:52','','','','','',NULL,'0','1','0','0.00','2017-04-05 00:42:42','2017-04-05 00:42:42','否','否','否','100','否','0'), ('15','牛奶','0.00','0.00','','是','是','2017-04-05 09:36:48','','','','','',NULL,'0','20','0','0.00','2017-04-05 09:36:31','2017-04-05 09:36:31','否','否','否','100','否','0'), ('16','肯德基','0.00','0.00','','是','是','2017-04-05 09:50:34','','','','','',NULL,'0','18','0','0.00','2017-04-05 09:50:22','2017-04-05 09:50:22','否','否','否','100','否','0'), ('17','赶集','0.00','0.00','','是','是','2017-04-05 09:53:41','','','','','',NULL,'0','16','0','0.00','2017-04-05 09:53:27','2017-04-05 09:53:27','否','否','否','100','否','0'), ('18','思考','0.00','0.00','','是','是','2017-04-05 09:56:51','','','','','',NULL,'0','16','0','0.00','2017-04-05 09:56:35','2017-04-05 09:56:35','否','否','否','100','否','0'), ('19','西红柿','0.00','0.00','','是','是','2017-04-05 10:09:48','','','','','','2017-04-05 18:17:03','0','17','0','0.00','2017-04-05 10:09:28','2017-04-05 10:09:28','否','否','否','100','否','0'), ('20','吊兰','0.00','0.00','','是','是','2017-04-05 10:20:58','','','','','','2017-04-05 23:52:41','0','20','0','0.00','2017-04-05 10:20:36','2017-04-05 10:20:36','否','否','否','100','否','0'), ('21','笔记本','0.00','0.00','','是','是','2017-04-05 10:22:29','','','','','',NULL,'0','20','0','0.00','2017-04-05 10:22:15','2017-04-05 10:22:15','否','否','否','100','否','0'), ('22','黑米','0.00','0.00','','是','是','2017-04-05 10:33:01','','','','','','2017-04-05 21:54:41','0','2','0','0.00','2017-04-05 10:32:46','2017-04-05 10:32:46','否','否','否','100','否','0'), ('23','婴儿车','0.00','0.00','','是','是','2017-04-05 10:58:01','','','','','',NULL,'0','22','0','0.00','2017-04-05 10:57:47','2017-04-05 10:57:47','否','否','否','100','否','0'), ('24','秋季','0.00','0.00','','是','是','2017-04-05 11:38:54','','','','','','2017-04-05 22:16:01','0','17','0','0.00','2017-04-05 11:38:42','2017-04-05 11:38:42','否','否','否','100','否','0'), ('25','你好粉红色','0.00','0.00','','是','是','2017-04-05 12:22:55','','','','','',NULL,'0','22','0','0.00','2017-04-05 12:22:48','2017-04-05 12:22:48','否','否','否','100','否','0'), ('26','冰箱','0.00','0.00','','是','是','2017-04-05 18:08:48','','','','','','2017-04-06 11:45:27','0','17','0','0.00','2017-04-05 18:08:31','2017-04-05 18:08:31','否','否','否','100','否','0'), ('27','香蕉','0.00','0.00','','是','是','2017-04-05 22:01:32','','','','','','2017-04-06 11:51:58','0','16','0','0.00','2017-04-05 22:01:23','2017-04-05 22:01:23','否','否','否','100','否','0'), ('28','可乐','0.00','0.00','','是','是','2017-04-05 22:06:58','','','','','','2017-04-06 00:45:21','0','22','0','0.00','2017-04-05 22:06:46','2017-04-05 22:06:46','否','否','否','100','否','0'), ('29','别克','0.00','0.00','','是','是','2017-04-05 23:29:23','','','','','','2017-04-06 11:34:05','0','17','0','0.00','2017-04-05 23:28:50','2017-04-05 23:28:50','否','否','否','100','否','0'), ('30','林肯','0.00','0.00','','是','是','2017-04-06 00:48:20','','','','','',NULL,'0','18','0','0.00','2017-04-06 00:47:52','2017-04-06 00:47:52','否','否','否','100','否','0');
INSERT INTO `s11_goods_attr` VALUES ('45','金色','1','49'), ('47','2017-01-01','5','49'), ('48','12','6','49'), ('62','1200','7','49'), ('63','500','7','49'), ('69','金色','1','40'), ('70','银色','1','40'), ('71','2017-01-01','5','40'), ('72','','6','40'), ('73','100','7','40'), ('74','500','7','40'), ('75','夏季','3','51'), ('76','冬季','3','51'), ('77','化纤','4','51'), ('78','M','8','51'), ('79','L','8','51'), ('80','XL','8','51'), ('81','金色','1','1'), ('82','银色','1','1'), ('83','黑色','1','1'), ('84','2017-01-01','5','1'), ('85','20寸','6','1'), ('86','1200','7','1'), ('87','500','7','1');
INSERT INTO `s11_goods_cat` VALUES ('37','20'), ('37','22'), ('38','23'), ('38','16'), ('38','22'), ('51','8'), ('51','10'), ('52','16'), ('53','22'), ('54','2'), ('3','4'), ('2','16'), ('2','8'), ('1','21'), ('4','21'), ('5','16'), ('6','16');
INSERT INTO `s11_goods_number` VALUES ('1','97','81,86'), ('1','1','81,87'), ('1','1','82,86'), ('1','0','82,87'), ('1','3','83,86'), ('1','2','83,87'), ('2','77','');
INSERT INTO `s11_goods_pic` VALUES ('38','Goods/2017-02-21/58ac06659b182.png','Goods/2017-02-21/thumb_0_58ac06659b182.png','Goods/2017-02-21/thumb_1_58ac06659b182.png','Goods/2017-02-21/thumb_2_58ac06659b182.png','28'), ('39','Goods/2017-02-21/58ac0709b1c7e.jpeg','Goods/2017-02-21/thumb_0_58ac0709b1c7e.jpeg','Goods/2017-02-21/thumb_1_58ac0709b1c7e.jpeg','Goods/2017-02-21/thumb_2_58ac0709b1c7e.jpeg','28'), ('43','Goods/2017-02-21/58ac278a9031f.jpg','Goods/2017-02-21/thumb_0_58ac278a9031f.jpg','Goods/2017-02-21/thumb_1_58ac278a9031f.jpg','Goods/2017-02-21/thumb_2_58ac278a9031f.jpg','28'), ('47','Goods/2017-02-21/58ac2e25873ab.jpg','Goods/2017-02-21/thumb_0_58ac2e25873ab.jpg','Goods/2017-02-21/thumb_1_58ac2e25873ab.jpg','Goods/2017-02-21/thumb_2_58ac2e25873ab.jpg','29'), ('48','Goods/2017-03-11/58c40ce59f40d.jpeg','Goods/2017-03-11/thumb_0_58c40ce59f40d.jpeg','Goods/2017-03-11/thumb_1_58c40ce59f40d.jpeg','Goods/2017-03-11/thumb_2_58c40ce59f40d.jpeg','51'), ('49','Goods/2017-03-11/58c40ce61462a.jpg','Goods/2017-03-11/thumb_0_58c40ce61462a.jpg','Goods/2017-03-11/thumb_1_58c40ce61462a.jpg','Goods/2017-03-11/thumb_2_58c40ce61462a.jpg','51'), ('50','Goods/2017-03-21/58d1108b12440.png','Goods/2017-03-21/thumb_0_58d1108b12440.png','Goods/2017-03-21/thumb_1_58d1108b12440.png','Goods/2017-03-21/thumb_2_58d1108b12440.png','1'), ('51','Goods/2017-03-21/58d1108c0ee47.jpeg','Goods/2017-03-21/thumb_0_58d1108c0ee47.jpeg','Goods/2017-03-21/thumb_1_58d1108c0ee47.jpeg','Goods/2017-03-21/thumb_2_58d1108c0ee47.jpeg','1'), ('52','Goods/2017-03-21/58d1108cf2072.jpg','Goods/2017-03-21/thumb_0_58d1108cf2072.jpg','Goods/2017-03-21/thumb_1_58d1108cf2072.jpg','Goods/2017-03-21/thumb_2_58d1108cf2072.jpg','1');
INSERT INTO `s11_member` VALUES ('1','jack','8ddcff3a80f4189ca1c9d4d902c3c909','','10000');
INSERT INTO `s11_member_level` VALUES ('1','普通会员','0','5000'), ('2','初级会员','5001','10000'), ('3','高级会员','10001','20000'), ('4','VIP会员','20001','16777215');
INSERT INTO `s11_member_price` VALUES ('1','18','44.00'), ('2','18','3.00'), ('3','18','2.00'), ('4','18','1.00'), ('1','10','44.00'), ('2','10','33.00'), ('3','10','4534.00'), ('2','16','1.00'), ('3','16','1.00'), ('4','16','1.00'), ('1','51','299.00'), ('2','51','288.00'), ('3','51','266.00'), ('4','51','222.00'), ('1','2','2888.00'), ('2','2','2788.00'), ('3','2','2688.00'), ('4','2','2488.00'), ('2','1','1799.00'), ('3','1','1699.00'), ('4','1','1599.00'), ('1','20','1.00'), ('2','20','200.00'), ('3','20','300.00'), ('4','20','400.00'), ('1','28','11.00'), ('2','28','12.00'), ('3','28','12.00'), ('4','28','12.00'), ('1','29','1.00'), ('2','29','3.00'), ('3','29','4.00'), ('4','29','5.00');
INSERT INTO `s11_order` VALUES ('2','1','1490448286','否','0','12772.00','赵睿','15333333333','北京','朝阳区','西三旗','光谷软件','0',''), ('3','1','1490448577','是','0','1799.00','赵睿','15333333333','北京','朝阳区','西二旗','光谷软件','0',''), ('4','1','1490538473','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('5','1','1490538473','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('6','1','1490538473','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('7','1','1490538473','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('8','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('9','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('10','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('11','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('12','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('13','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('14','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('15','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('16','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('17','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('18','1','1490538474','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('19','1','1490538475','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('20','1','1490538475','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('21','1','1490538475','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('22','1','1490538475','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('23','1','1490538475','否','0','2788.00','吴英雷','13344441111','上海','东城区','西三旗','西三旗','0',''), ('24','1','1490574049','否','0','1799.00','赵睿','15333333333','北京','朝阳区','西三旗','光谷软件','0',''), ('25','1','1490587135','否','0','2788.00','赵睿','15333333333','北京','朝阳区','西三旗','光谷软件','0',''), ('26','1','1490589026','否','0','1799.00','赵睿','15333333333','北京','朝阳区','西三旗','光谷软件','0','');
INSERT INTO `s11_order_goods` VALUES ('1','2','1','82,86','1','1799.00'), ('2','2','1','82,87','2','1799.00'), ('3','2','1','83,87','1','1799.00'), ('4','2','2','','2','2788.00'), ('5','3','1','81,86','1','1799.00'), ('6','4','2','','1','2788.00'), ('7','5','2','','1','2788.00'), ('8','6','2','','1','2788.00'), ('9','7','2','','1','2788.00'), ('10','8','2','','1','2788.00'), ('11','9','2','','1','2788.00'), ('12','10','2','','1','2788.00'), ('13','11','2','','1','2788.00'), ('14','12','2','','1','2788.00'), ('15','13','2','','1','2788.00'), ('16','14','2','','1','2788.00'), ('17','15','2','','1','2788.00'), ('18','16','2','','1','2788.00'), ('19','17','2','','1','2788.00'), ('20','18','2','','1','2788.00'), ('21','19','2','','1','2788.00'), ('22','20','2','','1','2788.00'), ('23','21','2','','1','2788.00'), ('24','22','2','','1','2788.00'), ('25','23','2','','1','2788.00'), ('26','24','1','81,86','1','1799.00'), ('27','25','2','','1','2788.00'), ('28','26','1','81,86','1','1799.00');
INSERT INTO `s11_privilege` VALUES ('1','0','商品模块','','',''), ('2','1','商品列表','Admin','Goods','lst'), ('3','2','添加商品','Admin','Goods','add'), ('4','2','修改商品','Admin','Goods','edit'), ('5','2','删除商品','Admin','Goods','delete'), ('6','1','分类列表','Admin','Category','lst'), ('7','6','添加分类','Admin','Category','add'), ('8','6','修改分类','Admin','Category','edit'), ('9','6','删除分类','Admin','Category','delete'), ('10','0','RBAC','','',''), ('11','10','权限列表','Admin','Privilege','lst'), ('12','11','添加权限','Privilege','Admin','add'), ('13','11','修改权限','Admin','Privilege','edit'), ('14','11','删除权限','Admin','Privilege','delete'), ('15','10','角色列表','Admin','Role','lst'), ('16','15','添加角色','Admin','Role','add'), ('17','15','修改角色','Admin','Role','edit'), ('18','15','删除角色','Admin','Role','delete'), ('19','10','管理员列表','Admin','Admin','lst'), ('20','19','添加管理员','Admin','Admin','add'), ('21','19','修改管理员','Admin','Admin','edit'), ('22','19','删除管理员','Admin','Admin','delete'), ('23','1','类型列表','Admin','Type','lst'), ('24','23','添加类型','Admin','Type','add'), ('25','23','修改类型','Admin','Type','edit'), ('26','23','删除类型','Admin','Type','delete'), ('27','23','属性列表','Admin','Attribute','lst'), ('28','27','添加属性','Admin','Attribute','add'), ('29','27','修改属性','Admin','Attribute','edit'), ('30','27','删除属性','Admin','Attribute','delete'), ('31','4','ajax删除商品属性','Admin','Goods','ajaxDelGoodsAttr'), ('32','4','ajax删除商品相册图片','Admin','Goods','ajaxDelImage'), ('33','0','会员管理','','',''), ('34','33','会员级别列表','Admin','MemberLevel','lst'), ('35','34','添加会员级别','Admin','MemberLevel','add'), ('36','34','修改会员级别','Admin','MemberLevel','edit'), ('37','34','删除会员级别','Admin','MemberLevel','delete'), ('38','1','品牌列表','Admin','Brand','lst');
INSERT INTO `s11_role` VALUES ('2','商品管理'), ('5','商品操作');
INSERT INTO `s11_role_pri` VALUES ('2','1'), ('2','2'), ('2','3'), ('2','4'), ('2','31'), ('2','32'), ('2','5'), ('2','6'), ('2','7'), ('2','8'), ('2','9'), ('2','23'), ('2','24'), ('2','25'), ('2','26'), ('2','27'), ('2','28'), ('2','29'), ('2','30'), ('2','38'), ('2','33'), ('2','34'), ('2','35'), ('2','36'), ('2','37'), ('5','1'), ('5','2'), ('5','3'), ('5','4'), ('5','31'), ('5','32'), ('5','5');
INSERT INTO `s11_sphinx_id` VALUES ('30');
INSERT INTO `s11_type` VALUES ('3','手机'), ('4','服装');
INSERT INTO `s11_yinxiang` VALUES ('1','2','质量不错','1'), ('2','2','手感好','2'), ('3','2','价格便宜','3'), ('4','2','客服漂亮','1'), ('5','2','发货快','2');
