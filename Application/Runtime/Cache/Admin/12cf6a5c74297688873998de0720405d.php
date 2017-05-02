<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 商品列表 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
<script src="/Public/umeditor/third-party/jquery.min.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $_page_btn_link; ?>"><?php echo $_page_btn_name; ?></a></span>
    <span class="action-span1"><a href="__GROUP__">管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $_page_title; ?> </span>
    <div style="clear:both"></div>
</h1>

<!--内容-->
<!--		引入布局文件		-->

<!--		搜索表单			-->
<div class="form-div">
    <form action="/index.php/Admin/Goods/lst" method="GET" name="searchForm">
    	<p>
    		分　　类：
    		<?php $catId = I('get.cat_id');?>
        	<select name="cat_id">
				<option value="">请选择</option>
			<?php foreach ($tree as $k=>$v): if($v['id'] == $catId) $selected = 'selected="selected"'; else $selected = ''; ?>
				<option <?php echo $selected;?> value="<?php echo $v['id']?>"><?php echo str_repeat('-' , 8*$v['level']) . $v['cat_name']?></option>
			<?php endforeach;?>
			</select>
    	</p>
    	<p>
    		商品品牌：
    		<?php buildSelect('brand','brand_id','id','brand_name',I('get.brand_id'));?>
    	</p>
    	<p>
    		商品名称：
    		<input value="<?php echo I('get.gn'); ?>" type="text" name="gn" size="60"/>
    	</p>
    		价　　格：
    		从 <input value="<?php echo I('get.fp'); ?>" type="text" name="fp" size="8"/>
    		到 <input value="<?php echo I('get.tp'); ?>" type="text" name="tp" size="8"/>
    	<p>
    		是否上架：
    		<input type="radio" name="ios" value="" <?php if(I('get.ios') == '') echo 'checked="checked"'; ?>/> 全部
    		<input type="radio" name="ios" value="是" <?php if(I('get.ios') == '是') echo 'checked="checked"'; ?>/> 上架
    		<input type="radio" name="ios" value="否" <?php if(I('get.ios') == '否') echo 'checked="checked"'; ?>/> 下架
    	</p>
    	<p>
    		添加时间：
    		从 <input id="fa" value="<?php echo I('get.fa'); ?>" type="text" name="fa" size="20"/>
    		到 <input id="ta" value="<?php echo I('get.ta'); ?>" type="text" name="ta" size="20"/>
    	</p>
    	<p>
    		排序方式:
    		<?php $odby = I('get.odby' , 'id_desc'); ?>
    		<input onclick="this.parentNode.parentNode.submit();" type="radio" name="odby" value="id_desc" <?php if($odby=='id_desc') echo 'checked="checked"'; ?>/>以添加时间降序
    		<input onclick="this.parentNode.parentNode.submit();" type="radio" name="odby" value="id_asc" <?php if($odby=='id_asc') echo 'checked="checked"'; ?>/>以添加时间升序
    		<input onclick="this.parentNode.parentNode.submit();" type="radio" name="odby" value="price_desc" <?php if($odby=='price_desc') echo 'checked="checked"'; ?>/>以价格降序
    		<input onclick="this.parentNode.parentNode.submit();" type="radio" name="odby" value="price_asc" <?php if($odby=='price_asc') echo 'checked="checked"'; ?>/>以价格升序
    	</p>
    	<p>
    		<input type="submit" value="搜索" />
    		<input type="button" onclick="location='/index.php/Admin/Goods/lst'" value="重输" />
    	</p>
    </form>
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>编号</th>
                <th>主分类</th>
                <th>扩展分类</th>
                <th>商品名称</th>
                <th>商品品牌</th>
                <th>商品图片</th>
                <th>市场价格</th>
                <th>本店价格</th>
                <th>上架</th>
                <th>权重</th>
                <th>添加日期</th>
                <th>修改日期</th>
                <th width="110px">操作</th>
            </tr>
            <?php foreach ($data as $k => $v): ?>
            <tr class="tron">
                <td align="center"><?php echo $v['id'];?></td>
                <td align="center"><?php echo $v['cat_name'];?></td>
                <td align="center"><?php echo $v['ext_cat_name'];?></td>
                <td align="center" class="first-cell"><span><?php echo $v['goods_name'];?></span></td>
                <td align="center" class="first-cell"><?php echo $v['brand_name'];?></td>
                <td align="center"><?php showImage($v['sm_logo']);?></td>
                <td align="center"><?php echo $v['market_price'];?></td>
                <td align="center"><?php echo $v['shop_price'];?></td>
                <td align="center"><?php echo $v['is_on_sale'];?></td>
                <td align="center"><?php echo $v['sort_num'];?></td>
                <td align="center"><?php echo $v['addtime'];?></td>
                <td align="center"><?php echo $v['edittime'];?></td>
                <td align="center">
                	<a href="<?php echo U('goods_number?id=' . $v['id']); ?>">库存量</a> | 
                	<a href="<?php echo U('edit?id=' . $v['id']); ?>">修改</a> | 
                	<a href="<?php echo U('delete?id=' . $v['id']); ?> " onclick="return confirm('确定删除吗?');">删除</a>
                </td>
            </tr>
            <?php endforeach;?>
        </table>

    <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
<!--                <td width="80%">&nbsp;</td>-->
                <td align="center" nowrap="true">
                   <?php echo $page; ?>
                </td>
            </tr>
        </table>
    <!-- 分页结束 -->
    </div>
</form>


<!-- 时间插件 -->
<script src="/Public/umeditor/third-party/jquery.min.js"></script>
<link href="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/datepicker-zh_cn.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.css" />
<script type="text/javascript" src="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="/Public/datetimepicker/time/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script>
//设置中文
$.timepicker.setDefaults($.timepicker.regional['zh-CN']);
//设置格式,显示时分
$("#fa").datetimepicker();
$("#ta").datetimepicker();
</script>

<!--引入行高亮显示一般放在最后-->
<script src="/Public/Admin/Js/tron.js"></script>


<div id="footer">
版权所有 &copy; 2016-2017 ****网络科技有限公司，并保留所有权利。</div>
</body>
</html>