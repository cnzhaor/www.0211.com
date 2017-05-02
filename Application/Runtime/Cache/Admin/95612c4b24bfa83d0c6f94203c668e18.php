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

<div class="main-div">
    <form name="main_form" method="POST" action="/index.php/Admin/Category/add.html" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
		<tr>	
			<td class="label">上级分类：</td>
			<td>
				<select name="parent_id">
					<option value="0">顶级分类</option>
				<?php foreach ($tree as $k=>$v): ?>
					<option value="<?php echo $v['id']?>"><?php echo str_repeat('-' , 8*$v['level']) . $v['cat_name']?></option>
				<?php endforeach;?>
				</select>
			</td>
		</tr>  
		<tr>
		    <td class="label">分类名称：</td>
		    <td>
		        <input  type="text" name="cat_name" value="" />
		    </td>
		</tr>
        <tr>
            <td class="label">推荐楼层：</td>
            <td>
                <input type="radio" name="is_floor" value="是"/> 是
                <input type="radio" name="is_floor" value="否" checked='checked'/> 否
            </td>
        </tr>
        <tr>
            <td colspan="99" align="center">
                <input type="submit" class="button" value=" 确定 " />
                <input type="reset" class="button" value=" 重置 " />
            </td>
        </tr>
        </table>
    </form>
</div>


<script>
</script>

<div id="footer">
版权所有 &copy; 2016-2017 ****网络科技有限公司，并保留所有权利。</div>
</body>
</html>