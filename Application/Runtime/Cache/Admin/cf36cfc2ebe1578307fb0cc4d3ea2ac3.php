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
    <form name="main_form" method="POST" action="/index.php/Admin/Role/edit/id/5/p/1.html" enctype="multipart/form-data" >
    	<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">角色名称：</td>
                <td>
                    <input  type="text" name="role_name" value="<?php echo $data['role_name']; ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">权限列表：</td>
                <td>
                	<?php foreach ($priData as $k=>$v): if(strpos(','.$rpData.',' , ','.$v['id'].',') !== false) $check = 'checked="checked"'; else $check = ''; ?>
                		<?php echo str_repeat('-' , 8*$v['level']);?>
                		<input <?php echo $check;?> level_id="<?php echo $v['level'];?>" type="checkbox" name="pri_id[]" value="<?php echo $v['id'];?>"/>
                		<?php echo $v['pri_name'];?><br>
                	<?php endforeach;?>
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
//为所有复选框绑定点击事件
$(':checkbox').click(function(){
	//获得当前点击的level_id
	var tmp_level_id = level_id = $(this).attr('level_id');
	//判断是选中还在取消
	if($(this).prop('checked'))
	{
		//选中子权限
		$(this).nextAll(':checkbox').each(function(k,v){
			if($(v).attr('level_id') > level_id)
				$(v).prop('checked','checked');
			else
				return false;
		});
		//选中上级权限
		$(this).prevAll(':checkbox').each(function(k,v){
			if($(v).attr('level_id') < tmp_level_id)
			{
				$(v).prop('checked' , 'checked');
				tmp_level_id--;
			}
		});
	}
	else
	{
		//取消选中子权限
		$(this).nextAll(':checkbox').each(function(k,v){
			if($(v).attr('level_id') > level_id)
				$(v).removeAttr('checked');
			else
				return false;
		});
	}
});
</script>

<div id="footer">
版权所有 &copy; 2016-2017 ****网络科技有限公司，并保留所有权利。</div>
</body>
</html>