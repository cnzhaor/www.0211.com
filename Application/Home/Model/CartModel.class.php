<?php
namespace Home\Model;
use Think\Model;
class CartModel extends Model 
{
	protected $insertFields = array('goods_id','goods_attr_id','goods_number');
	protected $_validate = array(
		array('goods_id', 'require', '未选择商品！', 1),
		array('goods_number', 'chkGoodsNumber', '商品库存不足！', 1, 'callback'),
		);
	//检查库存
	public function chkGoodsNumber($goodsNumber){
		//商品属性id
		$gid = I('post.goods_id');
		$gaid = I('post.goods_attr_id');
		//升序排序
		sort($gaid , SORT_NUMERIC);
		$gaid = (string)implode(',' , $gaid);
		//库存
		$gnModel = D('Admin/goods_number');
		$gn = $gnModel->field('goods_number')->where(array(
			'goods_id' => $gid,
			'goods_attr_id' => $gaid,
		))->find();
		return ($gn['goods_number'] >= $goodsNumber);
	}
	
	//重写父类add(),已登录存数据库,未登录存cookie
	public function add(){
		$memberId = session('m_id');
		//表单中的商品属性排序
		sort($this->goods_attr_id , SORT_NUMERIC);
		$this->goods_attr_id = (string)implode(',',$this->goods_attr_id);
		//如果已登录
		if($memberId){
			// 先把表单中的库存量存到这个变量中,否则调用find之后就没了
			$goodsNumber = $this->goods_number;
			//判断数据库中是否已经存在此商品
			$has = $this->field('id')->where(array(
				'member_id' => $memberId,
				'goods_id' => $this->goods_id,
				'goods_attr_id' => $this->goods_attr_id,
			))->find();
			//如果有
			if($has){
				$this->where(array(
					'id' => array('eq' , $has['id'])
				))->setInc('goods_number',$goodsNumber);
			}
			//如果没有
			else
				parent::add(array(
					'member_id' => $memberId,
					'goods_id' => $this->goods_id,
					'goods_attr_id' => $this->goods_attr_id,
					'goods_number' => $goodsNumber
				));
		}
		//未登录
		else{
			//cookie中取得购物车一维数组
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			//先拼一个下标
			$key = $this->goods_id .'-'.$this->goods_attr_id; 
			if(isset($cart[$key]))
				$cart[$key] += $this->goods_number;
			else
				$cart[$key] = $this->goods_number;
			//存cookie
				setcookie('cart' , serialize($cart) ,time() + 86400*30 ,'/');
		}
		return TRUE;
	}
	
	//将cookie中的数据移动到数据库中
	public function moveDataToDb(){
		$memberId = session('m_id');
		if($memberId){
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			foreach ($cart as $k =>$v){
				$_k = explode('-' , $k);
				//数据库有这件商品
				$has = $this->field('id')->where(array(
					'member_id' => $memberId,
					'goods_id' => $_k[0],
					'goods_attr_id' => $_k[1],
				))->find();
				if($has){
					$this->where(array(
						'id' => array('eq' , $has['id'])
					))->setInc('goods_number',$v);
				}
				//数据库没有这件
				else
					parent::add(array(
						'member_id' => $memberId,
						'goods_id' => $_k[0],
						'goods_attr_id' => $_k[1],
						'goods_number' => $v
					));
			}
			//clear cookie
			setcookie('cart' , '' , time()-1, '/');
		}
	}
	
	//购物车商品详情
	public function cartList(){
		/***********商品id***********/
		$memberId = session('m_id');
		//已登录
		if($memberId)
			$data = $this->where(array(
				'member_id' => array('eq' , $memberId)
			))->select();
		//未登录
		else{
			$_data = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			$data = array();
			foreach ($_data as $k=>$v){
				//下标中取出商品id和商品属性id
				$_k = explode('-' , $k);
				$data[] = array(
					'goods_id' => $_k[0],
					'goods_attr_id' => $_k[1],
					'goods_number' => $v
				);
			}
		}
		/***********商品详情***********/
		$gModel = D('Admin/goods');
		$gaModel = D('Admin/goods_attr');
		foreach ($data as $k=>&$v){
			//商品名称,商品LOGO
			$info = $gModel->field('goods_name,mid_logo')->find($v['goods_id']);
			$v['goods_name'] = $info['goods_name'];
			$v['mid_logo'] = $info['mid_logo'];
			//购买价格
			$v['price'] = $gModel->getMemberPrice($v['goods_id']);
			//计算 属性名称:属性值
			if($v['goods_attr_id'])
				$v['gaData'] = $gaModel->alias('a')
				->field('b.attr_name,a.attr_value')
				->join('__ATTRIBUTE__ b ON a.attr_id=b.id')
				->where(array(
					'a.id' => array('in' , $v['goods_attr_id'])
				))
				->select();
		}
		return $data;
	}
	/**
	 * 清空购物车
	 *
	 */
	public function clear(){
		$this->where(array(
			'member_id' => session('m_id')
		))->delete();
	}
}