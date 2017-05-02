<?php
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends BaseController 
{
	//库存量
	public function goods_number()
	{
//		header('Content-Type:text/html;charset=utf8');
		//接收商品ID
		$id = I('get.id');
		$gnModel = D('goods_number');
		if(IS_POST)
		{
			//先删除原库存
			$gnModel->where(array(
				'goods_id'=>array('eq' , $id)
			))->delete();
			//			dump($_POST);die;
			$gaid = I('post.goods_attr_id');
			$gn = I('post.goods_number');
			//计算商品属性id数量和库存量数量的比例
			$gaidCount = count($gaid);
			$gnCount = count($gn);
			$rate = $gaidCount/$gnCount;
			//循环库存量
			$_i = 0;//要取得属性的下标
			foreach ($gn as $k=>$v)
			{
				$_goodsAttrId = array();
				for($i=0; $i<$rate; $i++)
				{
					$_goodsAttrId[] = $gaid[$_i];
					$_i++;
				}
				sort($_goodsAttrId,SORT_NUMERIC);//数字形式排序
				$_goodsAttrId = (string)implode(',',$_goodsAttrId);
				$gnModel->add(array(
					'goods_id' => $id,
					'goods_attr_id' => $_goodsAttrId,
					'goods_number' => $v
				));
			}
			$this->success('操作成功!' , U("goods_number?id=$id"));
			exit;
		}

		//取出这件商品所有可选(多选)属性的值
		$gaModel = D('goods_attr');
		$gaData = $gaModel->alias('a')
		->field('a.*,b.attr_name')
		->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id=b.id')
		->where(array(
			'a.goods_id' => array('eq' , $id),
			'b.attr_type' => array('eq' , '可选')
		))->select();
		//二维数组转三维
		$_gaData = array();
		foreach ($gaData as $k=>$v)
		{
			$_gaData[$v['attr_name']][] = $v;
		}
		//先取出已经设置的库存
		$gnData = $gnModel->where(array(
			'goods_id' => $id,
		))->select();
//		dump($gnData);die;
		//设置页面信息
		$this -> assign(array(
			'gaData'=>$_gaData,
			'gnData'=>$gnData,
			'_page_title'=>'库存量',
			'_page_btn_name'=>'商品列表',
			'_page_btn_link'=>U('lst')
		));
		//显示表单
		$this->display();

	}
		
	//AJAX删除属性
	public function ajaxDelAttr()
	{
		//addslashes() 函数返回在预定义字符之前添加反斜杠的字符串。防止数据库被攻击
		$goodsId = addslashes(I('get.goods_id'));
		$gaid = addslashes(I('get.gaid'));
		$gaModel = D('goods_attr');
		$gaModel->delete($gaid);
		//删除有这个属性的商品库存
		$gnModel = D('goods_number');
		$gnModel->where(array(
			'goods_id' => array('EXP' , "=$goodsId AND FIND_IN_SET($gaid , goods_attr_id)")
		))->delete();
	}
	
	//AJAX处理根据类型获取属性
	public function ajaxGetAttr()
	{
		$type_id = I('get.type_id');
		$attrModel = D('attribute');
		$attrData = $attrModel->where(array(
			'type_id' => array('eq' , $type_id)
		))->select();
		echo json_encode($attrData);
	}
	
	//AJAX删除图片
	public function	ajaxDelPic()
	{
		$picid = I('get.picid');
		//根据picid删除磁盘中的图片
		$gpModel = D('goods_pic');
		$pic = $gpModel->field('pic,sm_pic,mid_pic,big_pic')->find($picid);
		//从磁盘中删除图片
		deleteImage($pic);
		//从数据库中删除图片
		$gpModel->delete($picid);
	}
	//添加
	public function add()
	{
		//判断用户是否提交
		if(IS_POST)	
		{
//			dump($_POST);die;
			//上传图片会遇到限制,脚本执行时间【默认PHP一个脚本只能执行30秒】,可以在上传图片之前调用
			set_time_limit(0);
			//实例化模型
			$model = D('goods');
			//调用create()方法,使用I函数过滤表单,1代表添加数据
			if($model->create(I('post.') ,1))
			{
				if($model->add())
				{
					//显示成功信息,等待1秒跳转		
					$this->success('操作成功!' , U('lst'));
					exit;
				}
			}
			//此处代表上传失败
			//从模板中获取失败原因
			$error = $model->getError();
			//控制器显示错误信息,3秒跳回前一页
			$this->error($error);
		}	
		//取出所有分类树形结构
		$model = D('Category');
		$tree = $model->getTree();
		
		//取出所有会员级别
		$mlModel = D('member_level');
		$mlData = $mlModel->select();
		//设置页面信息
		$this -> assign(array(
			'tree' => $tree,
			'mlData'=>$mlData,
			'_page_title'=>'添加商品',
			'_page_btn_name'=>'商品列表',
			'_page_btn_link'=>U('lst')
		));
		//显示表单
		$this->display();
	}
	//删除
	public function delete()
	{
		$model = D('goods');
		if(FALSE !== $model->delete(I('get.id')))
			$this->success('删除成功!' , U('lst'));
		else 
			$this->error('删除失败!原因:' . $model->getError());
	}
	//修改
	public function edit()
	{
		//获取要修改的商品ID
		$id = I('get.id');
		$model = D('goods');
		if(IS_POST)	
		{			
//			dump($_POST);die;
			//调用create()方法,使用I函数过滤表单,2代表修改数据
			if($model->create(I('post.') ,2))
			{
//				dump($model->save());
//				$model->save();
//				dump($model->save());die;
				if(FALSE !== $model->save())//save()返回值,成功返回受影响的条数[没改返回0],失败返回false
				{
					$this->success('操作成功!' , U('lst'));
					exit;
				}
			}
			$error = $model->getError();
			$this->error($error);
		}
		//取出扩展分类id
		$gcModel = D('goods_cat');
		$gcData = $gcModel->field('cat_id')->where(array(
		goods_id =>array('eq' , $id)
		))->select();
		//取出所有分类树形结构
		$catModel = D('Category');
		$tree = $catModel->getTree();
		//根据ID获取要修改的原信息
		$data = $model -> find($id);
		$this -> assign('data' , $data);
		//取出所有会员级别
		$mlModel = D('member_level');
		$mlData = $mlModel->select();
		//取出这件商品已经设置好的会员价格		
		$mpModel = D('member_price');
		//取出会员价格的二维数组
		$mpData = $mpModel->where(array(
		'goods_id'=>array('eq' , $id)
		))->select();
		//转换为一维数组
		$_mpData = array();
		foreach ($mpData as $k=>$v)
		{
			$_mpData[$v['level_id']] = $v['price'];
		}
//		dump($mpData);
//		dump($_mpData);
		//取出相册中的图片
		$gpModel = D('goods_pic');
		$gpData = $gpModel->field('id,mid_pic')->where(array(
		'goods_id'=>array('eq' , $id)
		))->select();
		//取出当前类型下所有的属性值
		$attrModel = D('Attribute');
		$attrData = $attrModel->alias('a')
		->field('a.*,b.attr_value,b.id goods_attr_id')
		->join('LEFT JOIN __GOODS_ATTR__ b ON (a.id=b.attr_id AND b.goods_id='.$id.')')
		->where(array(
			'a.type_id' => array('eq' , $data['type_id'])
		))->select();
//		dump($attrData);die;
		//设置页面信息
		$this -> assign(array(
			'attrData'=>$attrData,
			'gcData'=>$gcData,
			'tree'=>$tree,
			'mlData'=>$mlData,
			'mpData'=>$_mpData,
			'gpData'=>$gpData,
			'_page_title'=>'修改商品',
			'_page_btn_name'=>'商品列表',
			'_page_btn_link'=>U('lst')
		));
		$this->display();
	}
	//商品列表
	public function lst()
	{
		//取出所有分类树形结构
		$catModel = D('Category');
		$tree = $catModel->getTree();		
		//实例化模型
		$model = D('goods');
		//返回数据和翻页
		$data = $model -> search();
		$this -> assign($data);
		//设置页面布局信息
		$this -> assign(array(
			'tree'=>$tree,
			'_page_title'=>'商品列表',
			'_page_btn_name'=>'添加商品',
			'_page_btn_link'=>U('add')
		));
		//展示
		$this ->display();
	}
}