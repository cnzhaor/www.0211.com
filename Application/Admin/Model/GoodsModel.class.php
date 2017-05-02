<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model 
{
	//添加数据时允许create()方法接收的字段
	protected $_insertFields = 'goods_name,market_price,shop_price,is_on_sale,goods_desc,brand_id,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_new,is_hot,is_best,sort_num,is_floor';	
	//修改数据时允许create()方法接收的字段
	protected $_updateFields = 'id,goods_name,market_price,shop_price,is_on_sale,goods_desc,brand_id,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_new,is_hot,is_best,sort_num,is_floor';
	//定义验证规则
	protected $_validate = array(
		array('cat_id','require','必须选择主分类!	',1),
		array('goods_name','require','商品名称不能为空!	',1),
		array('market_price','currency','市场价格必须是货币类型!',1),
		array('shop_price','currency','本店价格必须是货币类型!',1),
	);
	//定义钩子方法,会在添加数据之前自动被调用(处理商品图片,记录添加时间,过滤商品描述)
	protected function _before_insert(&$data , $option)
	//$data表示即将插入数据库的表单数据(数组)是修改函数外部的变量,用引用传递,$option中带有要修改数据的ID
	{
		/*			处理图片			*/
		//判断有没有选择图片
		if($_FILES['logo']['error'] == 0)
		{
			$ret = uploadOne('logo', 'Goods', array(
			array(700, 700),
			array(350, 350),
			array(130, 130),
			array(50, 50),
			));
			if($ret['ok'] == 1)
			{
				$data['logo'] = $ret['images'][0];   // 原图地址
				$data['mbig_logo'] = $ret['images'][1];   // 第一个缩略图地址
				$data['big_logo'] = $ret['images'][2];   // 第二个缩略图地址
				$data['mid_logo'] = $ret['images'][3];   // 第三个缩略图地址
				$data['sm_logo'] = $ret['images'][4];   // 第四个缩略图地址
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
		}
		//获得当前时间插入到表单进而插入数据库
		$data['addtime'] = date('Y-m-d H:i:s');
		//过滤goods_desc
		$data['goods_desc'] = removeXSS($_POST['goods_desc']);
	}
	//定义钩子方法,会在修改数据之前自动被调用(处理新商品图片删除旧商品图片,记录修改时间,过滤描述)
	protected function _before_update(&$data , $option)
	{
		//$option中带有要修改数据的ID
		$id = $option['where']['id'];
		//标记商品被修改需要重建索引
		$data['is_updated'] = 1;
		//sphinx
		require('./sphinxapi.php');
		$sph = new \SphinxClient();
		$sph->SetServer('localhost' , '9312');
		//设置sphinx中此条记录的属性is_updated=1,用来过滤
		$sph->UpdateAttributes('goods' , array('is_updated') , array($id=>array(1)));
		
		/*		修改商品属性		*/
		$gaid = I('post.goods_attr_id');//获得已有的商品属性id
		$attrValue = I('post.attr_value');//获得提交的属性值
		$gaModel = D('goods_attr');
		$_i = 0;//循环次数
		foreach ($attrValue as $k=>$v)
		{
			foreach ($v as $k1=>$v1)
			{
//				$gaModel->execute('REPLACE INTO s11_goods_attr VALUES("'.$gaid[$_i].'","'.$v1.'","'.$k.'","'.$id.'")');
				//添加
				if($gaid[$_i] == '')
					$gaModel->add(array(
						'goods_id' => $id,
						'attr_id'  => $k,
						'attr_value' =>$v1,
					));
				//修改
				else 
					$gaModel->where(array(
						'id' => array('eq' , $gaid[$_i]),
					))->setField('attr_value' , $v1);
				$_i++;
			}
		}
		
		
		/*			处理扩展分类		*/
		$gcModel = D('goods_cat');
		//删除原来的扩展分类
		$gcModel->where(array(
		'goods_id'=>array('eq' , $id)
		))->delete();
		$ecid = I('post.ext_cat_id');
//		dump($_POST);die;
		if($ecid)
		{
			foreach ($ecid as $k=>$v)
			{
				if(empty($v))
					continue;
				$gcModel->add(array(
				'cat_id' =>$v,
				'goods_id'=>$id
				));
			}
		}
		/*			处理图片			*/
		//判断有没有选择图片
		if($_FILES['logo']['error'] == 0)
		{
			$ret = uploadOne('logo', 'Goods', array(
			array(700, 700),
			array(350, 350),
			array(130, 130),
			array(50, 50),
			));
			if($ret['ok'] == 1)
			{
				$data['logo'] = $ret['images'][0];   // 原图地址
				$data['mbig_logo'] = $ret['images'][1];   // 第一个缩略图地址
				$data['big_logo'] = $ret['images'][2];   // 第二个缩略图地址
				$data['mid_logo'] = $ret['images'][3];   // 第三个缩略图地址
				$data['sm_logo'] = $ret['images'][4];   // 第四个缩略图地址
				/*			删除旧图片 			*/
				//查询出旧图片地址
				$oldLogo = $this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($id);
				//从硬盘上删除
				deleteImage($oldLogo);
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
		}
				
		//获得当前时间插入到表单进而插入数据库
		$data['edittime'] = date('Y-m-d H:i:s');
		//过滤goods_desc
		$data['goods_desc'] = removeXSS($_POST['goods_desc']);
	}
	//定义钩子方法,会在删除数据之前自动被调用(删除商品图片,相册图片及数据,会员价格数据,扩展分类)
	protected function _before_delete($option) //此处没有$data参数
	{
		$id = $option['where']['id'];//要删除的商品id
		/*		删除商品库存		*/
		$gnModel = D('goods_number');
		$gnModel->where(array(
			'goods_id' => array('eq' , $id)
		))->delete();	
		/*		删除商品属性		*/
		$gaModel = D('goods_attr');
		$gaModel->where(array(
			'goods_id' => array('eq' , $id)
		))->delete();
		/*		删除扩展分类		*/
		$gcModel = D('goods_cat');
		$gcModel->where(array(
		'goods_id'=>array('eq' , $id)
		))->delete();
		/*		删除相册中的图片		*/
		$gpModel = D('goods_pic');
		$pics = $gpModel->field('pic,sm_pic,big_pic,mid_pic')
		->where(array(
			'goods_id'=>array('eq' , $id)
		))->select();
		//删除磁盘上的图片,遍历二维数组
		foreach ($pics as $k=>$v)
			deleteImage($v);
		//删除数据库的记录
		$gpModel -> where(array(
			'goods_id'=>array('eq' , $id)
		))->delete();
		/*			处理会员价格			*/
		$mpModel = D('member_price');
		//先删除会员价格
		$mpModel->where(array(
			'goods_id' => array('eq' , $id)
		))->delete();
		/*			删除旧图片 			*/
		$oldLogo = $this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($id);
		//从硬盘上删除
		deleteImage($oldLogo);
	}
	//定义钩子方法,会在添加数据之后自动被调用,$data['id']就是新添加的商品id(处理相册图片,会员价格,扩展分类)
	protected function _after_insert($data , $option)
	{
		/*		处理商品属性		*/
		$attrValue = I('post.attr_value');
		$gaModel = D('goods_attr');
		foreach ($attrValue as $k => $v)
		{
			//去重
			$v = array_unique($v);
			foreach ($v as $k1 => $v1)
			{
				$gaModel->add(array(
					'goods_id' => $data['id'],
					'attr_id' => $k,
					'attr_value' => $v1
				));
			}
		}
		/*		处理扩展分类		*/
		$ecid = I('post.ext_cat_id');
		if($ecid)
		{
			$gcModel = D('goods_cat');
			foreach ($ecid as $k=>$v)
			{
				if(empty($v))
					continue;
//				dump($data);die;
				$gcModel->add(array(
					'cat_id'=>$v,
					'goods_id'=>$data['id']
				));
			}
		}
		/*		处理相册图片		*/
		if(isset($_FILES['pic']))
		{
			$pics = array();
			foreach ($_FILES['pic']['name'] as $k=>$v)
			{
				$pics[] = array(
					'name'=>$v,
					'type'=>$_FILES['pic']['type'][$k],
					'tmp_name'=>$_FILES['pic']['tmp_name'][$k],
					'error'=>$_FILES['pic']['error'][$k],
					'size'=>$_FILES['pic']['size'][$k],
				);
			}
			$_FILES = $pics;//uploadOne()要在$_FILES中找图片
			$gpModel = D('goods_pic');
			foreach ($pics as $k=>$v)
			{
				if($v['error'] == 0)
				{
					$ret = uploadOne( $k , 'Goods', array(
					array(650, 650),
					array(350, 350),
					array(50, 50),
					));
					if($ret['ok'] == 1)
					{
						$gpModel->add(array(
							'pic' => $ret['images'][0],
							'big_pic' => $ret['images'][1],
							'mid_pic' => $ret['images'][2],
							'sm_pic' => $ret['images'][3],
							'goods_id' => $data['id']
						));
					}
					else 
					{
						$this->error = $ret['error'];
						return FALSE;
					}
				}
			}
		}
		/*处理会员价格*/
		$mpModel = D('member_price');
		$mp = I('post.member_price');
		foreach ($mp as $k=>$v)
		{
			//转换为浮点型过滤非空和非货币
			$_v = (float)$v;
			if($_v>0)
			{
				$mpModel->add(array(
					'price'=>$_v,
					'level_id'=>$k,
					'goods_id'=>$data['id']
				));
			}
		}
	}
	//定义钩子方法,会在修改数据之后自动被调用(处理相册图片,修改会员价格)
	protected function _after_update($data , $option)
	{
		$id = $option['where']['id'];//$option中带有要修改数据的ID
		/*			处理会员价格			*/
		$mp = I('post.member_price');
		$mpModel = D('member_price');
		//先删除原来的会员价格
		$mpModel->where(array(
			'goods_id' => array('eq' , $id)
		))->delete();
		//添加新数据
		foreach ($mp as $k=>$v)
		{
			//转换为浮点型过滤非空和非货币
			$_v = (float)$v;
			if($_v>0)
			{
				$mpModel->add(array(
					'price'=>$_v,
					'level_id'=>$k,
					'goods_id'=>$id
				));
			}
		}
		/*		处理相册图片		*/
		if(isset($_FILES['pic']))
		{
			$pics = array();
			foreach ($_FILES['pic']['name'] as $k=>$v)
			{
				$pics[] = array(
					'name'=>$v,
					'type'=>$_FILES['pic']['type'][$k],
					'tmp_name'=>$_FILES['pic']['tmp_name'][$k],
					'error'=>$_FILES['pic']['error'][$k],
					'size'=>$_FILES['pic']['size'][$k],
				);
			}
			$_FILES = $pics;//uploadOne()要在$_FILES中找图片
			$gpModel = D('goods_pic');
			foreach ($pics as $k=>$v)
			{
				if($v['error'] == 0)
				{
					$ret = uploadOne( $k , 'Goods', array(
					array(650, 650),
					array(350, 350),
					array(50, 50),
					));
					if($ret['ok'] == 1)
					{
						$gpModel->add(array(
							'pic' => $ret['images'][0],
							'big_pic' => $ret['images'][1],
							'mid_pic' => $ret['images'][2],
							'sm_pic' => $ret['images'][3],
							'goods_id' => $data['id']
						));
					}
					else 
					{
						$this->error = $ret['error'];
						return FALSE;
					}
				}
			}
		}
	}
	//实现翻页 搜索 排序 取数据
	public function search($perPage = 15)
	{
		/*			搜索			*/
		$where = array();
		//主分类
		$gc = I('get.cat_id');
		if($gc)
		{
			//先查处这个分类下所有的商品id
			$gids = $this->getGoodsIdByCatId($gc);
			$where['a.id'] = array('in' , $gids); 			
		}
		//商品名称
		$gn = I('get.gn');
		if($gn)
			$where['a.goods_name'] = array('like' , "%$gn%"); 
		//商品品牌
		$gb = I('get.brand_id');
		if($gb)
			$where['brand_id'] = array('eq' , $gb); 
		//价格
		$fp = I('get.fp');
		$tp = I('get.tp');
		if($fp && $tp)
			$where['a.shop_price'] = array('between' , array($fp , $tp));
		elseif($fp)
			$where['a.shop_price'] = array('egt' , $fp);
		elseif($tp)
			$where['a.shop_price'] = array('elt' , $tp);
		//是否上架
		$ios = I('get.ios');
		if($ios)
			$where['a.is_on_sale'] = array('eq' , $ios);
		//添加时间
		$fa = I('get.fa');
		$ta = I('get.ta');
		if($fa && $ta)
			$where['a.addtime'] = array('between' , array($fa , $ta));
		elseif($fa)
			$where['a.addtime'] = array('egt' , $fa);
		elseif($ta)
			$where['a.addtime'] = array('elt' , $ta);
		
		/*			翻页			*/
		//取出总的记录数
		$count = $this->alias('a')->where($where)->count();
		//实例化翻页类
		$page = new \Think\Page($count , $perPage);			//生成页面下面上一页、下一页的字符串
		//设置翻页样式
		$page -> setConfig('next','下一页');
		$page -> setConfig('prev','上一页');
		$pageString = $page -> show();
		
		/*			排序			*/
		$orderby = 'a.id';
		$orderway = 'desc';
		$odby = I('get.odby');
		if($odby)
		{
			if($odby == 'id_asc')
				$orderway = 'asc';
			elseif ($odby == 'price_desc')
				$orderby = 'a.shop_price';
			elseif ($odby == 'price_asc')
			{
				$orderway = 'asc';
				$orderby = 'a.shop_price';
			}
		}
		
		
		/*		取得当前页的数据		*/
		$data = $this->order("$orderby $orderway")//排序,order("传变量时用双引号")
		->alias('a')//别名
		->field('a.*,b.brand_name,c.cat_name,GROUP_CONCAT(e.cat_name SEPARATOR "<br>") ext_cat_name')//字段
		->join('LEFT JOIN __BRAND__ b ON a.brand_id=b.id
				LEFT JOIN __CATEGORY__ c ON a.cat_id=c.id
				LEFT JOIN __GOODS_CAT__ d ON a.id=d.goods_id
				LEFT JOIN __CATEGORY__ e ON d.cat_id=e.id')//外连接
		->where($where)//搜索
		->limit($page->firstRow . ',' . $page->listRows)//分页
		->group('a.id')
		->select();
		
		/*		返回数据		*/
		return array(
			'data' => $data,
			'page' => $pageString
		);
	}
	/**
	 * 获取搜索页一个分类一页的商品
	 *
	 * @param unknown_type $catId
	 * @param unknown_type $pageSize
	 */
	public function cat_search($catId , $pageSize = 60){
		/***********************搜索****************************/
		//商品ID
		$goodsId = $this->getGoodsIdByCatId($catId);
		$where['a.id'] = array('in' , $goodsId);
		//地址栏:http://www.0211.com/index.php/Home/Search/cat_search/cat_id/2/brand_id/1-meizu/price/0-2899/attr_1/金色-颜色
		//品牌
		$brandId = I('get.brand_id');
		if($brandId)
			$where['a.brand_id'] = array('eq' , (int)$brandId);
		//价格
		$price = I('get.price');
		if($price){
			$price = explode('-' , $price);
			$where['a.shop_price'] = array('between' , $price);
		}
		/***********************商品搜索开始****************************/
		$gaModel = D('goods_attr');
		$attrGoodsId = NULL;//根据属性搜索出来的商品ID
		//找出属性参数
		foreach ($_GET as $k=>$v){
			if(strpos($k , 'attr_') === 0){
				$attrId = str_replace('attr_' , '' , $k);
				$attrName = strrchr($v , '-');
				$attrValue = str_replace($attrName , '' , $v);
				//根据属性ID和属性值搜索出 商品ID,返回字符串格式: 1,2,3,4,5,6
				$gids = $gaModel->field('GROUP_CONCAT(goods_id) gids')->where(array(
					'attr_id' => array('eq' , $attrId),
					'attr_value' => array('eq' , $attrValue)
				))->find();
				
				//搜到了商品
				if($gids['gids']){
					$gids['gids'] = explode(',',$gids['gids']);
					
					//第一个属性
					if($attrGoodsId === NULL)
						$attrGoodsId = $gids['gids'];
					else{
						//求商品ID的交集
						$attrGoodsId = array_intersect($attrGoodsId , $gids['gids']);
						//两次搜到的商品没有交集
						if(empty($attrGoodsId)){
							//不可能满足的条件
							$where[a.id] = array('eq' , 0);
							break;
						}
						
					}
				}
				
				//没搜到商品
				else{
					//前几次的交集结果清空
					$attrGoodsId = array();
					//不可能满足的条件
					$where['a.id'] = array('eq' , 0);
					break;
				}
			}
		}
		if($attrGoodsId)
			$where['a.id'] = array('IN' , $attrGoodsId);
		/***********************商品搜索结束****************************/
		
		/***********************翻页****************************/
		$count = $this->alias('a')->field('COUNT(a.id) goods_count,GROUP_CONCAT(a.id) goods_id')->where($where)->find();
		//$count = $this->alias('a')->where($where)->count();
		$data['goods_id'] = explode(',' , $count['goods_id']);
		$page = new \Think\Page($count['goods_count'] , $pageSize);
		//配置翻页样式
		$page->setConfig('prev' , '上一页');
		$page->setConfig('next' , '下一页');
		$data['page'] = $page->show();
		/***********************排序****************************/
		$oderby = 'x1';//默认字段
		$oderway = 'desc';//默认方式
		$odby = I('get.odby');
		if($odby){
			if($odby == 'addtime')
				$oderby = 'a.addtime';
			if(strpos($odby , 'price_') === 0){
				$oderby = 'a.shop_price';
				if($odby == 'price_asc')
				$oderway = 'asc';
			}
		}
		/***********************取数据****************************/
		$data['data'] = $this->alias('a')
		->field('a.id,a.goods_name,a.mid_logo,a.shop_price,SUM(b.goods_number) x1 ')
		->join('LEFT JOIN __ORDER_GOODS__ b 
				ON (a.id=b.goods_id 
				AND 
				b.order_id IN(SELECT id FROM __ORDER__ WHERE pay_status="是"))')
		->where($where)
		->group('a.id')
		->limit($page->firstRow.','.$page->listRows)
		->order("$oderby $oderway")
		->select();
		
		
		return $data;
	}
	
	/**
	 * 取出一个分类下所有商品(主分类和扩展分类)
	 */
	public function getGoodsIdByCatId($catId)
	{
		//先取出所有子分类id
		$catModel = D('Admin/category');
		$children = $catModel->getChildren($catId);
		$children[] = $catId;
		//取出主分类下的商品id
		$gids = $this->field('id')->where(array(
			'cat_id'=>array('in' , $children)
		))->select();
		//取出扩展分类下的商品id
		$gcModel = D('goods_cat');
		$gids1 = $gcModel->field('DISTINCT goods_id id')->where(array(
			'cat_id'=>array('in' , $children)
		))->select();
		//合并两个二维数组,考虑到其中一个为空的情况
		if($gids && $gids1)
			$gids = array_merge($gids , $gids1);
		elseif ($gids1)
			$gids = $gids1;
		//二维转一维
		$id = array();
		foreach ($gids as $k=>$v)
		{
			if( !in_array($v['id'], $id))
				$id[] = $v['id'];
		}
		return $id;
	}
	/**
	 * 取出疯狂抢购的商品
	 * 
	 * */
	public function getPromoteGoods($limit = 5)
	{
		$today = date('Y-m-d H:i:s');
		return $this->field('id,goods_name,mid_logo,promote_price')
		->where(array(
			'promote_price' =>array('gt' , 0),
			'promote_start_date' =>array('elt' , $today),
			'promote_end_date' =>array('egt' , $today),
			'is_on_sale' =>array('eq' , '是'),
		))
		->limit($limit)
		->select();
	}
	/**
	 * 取出推荐的热卖,精品,新品
	 * 
	 * */
	public function getRecGoods($recType , $limit=5){
		return $this->field('id,goods_name,mid_logo,shop_price')
		->where(array(
			"$recType" => array('eq' , '是'),
			'is_on_sale' => array('eq' , '是')
		))
		->limit($limit)
		->order('sort_num')
		->select();
	}
	
	//获取会员价格
	public function getMemberPrice($goodsId){
		$today = date('Y-m-d H:i:s');
		$levelId = session('level_id');
		//促销价格
		$promotePrice = $this->field('promote_price')->where(array(
			'id'=>array('eq' , $goodsId),
			'promote_price' =>array('gt' , 0),
			'promote_start_date' =>array('elt' , $today),
			'promote_end_date' =>array('egt' , $today),
			'is_on_sale' =>array('eq' , '是'),
		))->find();
		//已经登陆
		if($levelId){
			$mpModel = D('member_price');
			$mpData = $mpModel->field('price')->where(array(
				'level_id' => array('eq' , $levelId),
				'goods_id' => array('eq' , $goodsId),
			))->find();
			//这个级别有会员价格
			if($mpData['price']){
				if($promotePrice['promote_price'])
					return min($mpData['price'] , $promotePrice['promote_price']);
				else 
					return $mpData['price'];
			}
			//没有会员价格
			else{
				$p = $this->field('shop_price')->find($goodsId);
				if($promotePrice['promote_price'])
					return min($p['shop_price'] , $promotePrice['promote_price']);
				else 
					return $p['shop_price'];
			}
		}
		//未登陆
		else{
		$p = $this->field('shop_price')->find($goodsId);
		if($promotePrice['promote_price'])
			return min($p['shop_price'] , $promotePrice['promote_price']);
		else 
			return $p['shop_price'];
		}
	}
}