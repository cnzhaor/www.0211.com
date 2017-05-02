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



<!-- 列表 -->
<div class="list-div" id="listDiv">
<form method="POST" action="/index.php/Admin/Goods/goods_number/id/1.html">
	<table cellpadding="3" cellspacing="1">
    	<!--   循环输出属性名称 	-->
    	<tr>
			<?php foreach ($gaData as $k => $v): ?>   
				<th><?php echo $k;?></th>      
			<?php endforeach; ?> 
			<th>库存量</th>
			<th width="10%">操作</th>
        </tr>
        <?php if($gnData):?>
        <?php foreach ($gnData as $k0=>$v0):?>
		<tr class="tron">
			<?php  $gaCount = count($gaData); foreach ($gaData as $k=>$v):?>
				<td align="center">
					<select name="goods_attr_id[]">
						<option value="">请选择</option>
						<?php foreach ($v as $k1=>$v1): $_attr = explode(',',$v0['goods_attr_id']); if(in_array($v1['id'],$_attr)) $select = 'selected="selected"'; else $select = ''; ?>
							<option <?php echo $select;?> value="<?php echo $v1['id'];?>"><?php echo $v1['attr_value'];?></option>
						<?php endforeach;?>
					</select>
				</td>
			<?php endforeach;?>
			<td align="center" width='30%'><input type="text" name="goods_number[]" value="<?php echo $v0['goods_number'];?>"/></td>
			<td align="center"><input onclick="addNewTr(this);" type="button" value="<?php echo $k0 == 0 ? '+' : '-';?>"/></td>
		</tr>
		<?php endforeach;?>
		<?php else: ?>
		<tr class="tron">
			<?php  $gaCount = count($gaData); foreach ($gaData as $k=>$v):?>
				<td align="center">
					<select name="goods_attr_id[]">
						<option value="">请选择</option>
						<?php foreach ($v as $k1=>$v1):?>
							<option value="<?php echo $v1['id'];?>"><?php echo $v1['attr_value'];?></option>
						<?php endforeach;?>
					</select>
				</td>
			<?php endforeach;?>
			<td align="center" width='30%'><input type="text" name="goods_number[]" value=""/></td>
			<td align="center"><input onclick="addNewTr(this);" type="button" value="+"/></td>
		</tr>	
		<?php endif;?>
		<tr id='submit'>
			<td align="center" colspan="<?php echo $gaCount+2;?>"><input type="submit" value="提交"/></td>
		</tr>
	</table>
</form>
</div>

<script>
function addNewTr(btn)
{
	var tr = $(btn).parent().parent();
	if($(btn).val() == '+')
	{
		//clone(true) 规定需复制事件处理程序。
		var newTr = tr.clone(true);
		newTr.find(':button').val('-');
		$('#submit').before(newTr);
	}
	else
		tr.remove();
}
</script>

<!--jquery-->
<script src="/Public/umeditor/third-party/jquery.min.js"></script>

<!--行高亮显示-->
<script src="/Public/Admin/Js/tron.js"></script>

<div id="footer">
版权所有 &copy; 2016-2017 ****网络科技有限公司，并保留所有权利。</div>
</body>
</html>