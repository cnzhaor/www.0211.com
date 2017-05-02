<?php
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model 
{
	//下单时允许接收的字段
	protected $insertFields = array('shr_name','shr_tel','shr_province','shr_city','shr_area','shr_address');
	//表单验证规则
	protected $_validate = array(
		array('shr_name', 'require', '收货人姓名不能为空！', 1, 'regex', 3),
		array('shr_tel', 'require', '收货人电话不能为空！', 1, 'regex', 3),
		array('shr_province', 'require', '所在省份不能为空！', 1, 'regex', 3),
		array('shr_city', 'require', '所在城市不能为空！', 1, 'regex', 3),
		array('shr_area', 'require', '所在地区不能为空！', 1, 'regex', 3),
		array('shr_address', 'require', '所在地址不能为空！', 1, 'regex', 3),
	);
	
	public function search($pageSize = 20){
		/**************************************** 搜索 ****************************************/
		$memberId = session('m_id');
		$where['member_id'] = array('eq', $memberId);
		$noPayCount = $this->where(array(
			'member_id' => $memberId,
			'pay_status' => '否'
		))->count();
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')
		->field('a.id,a.shr_name,a.total_price,a.pay_status,a.addtime,GROUP_CONCAT(DISTINCT c.sm_logo) logo')
		->join('LEFT JOIN __ORDER_GOODS__ b ON a.id=b.order_id
				LEFT JOIN __GOODS__ c ON b.goods_id=c.id')
		->where($where)
		->group('a.id')
		->limit($page->firstRow.','.$page->listRows)
		->select();
		$data['noPayCount'] = $noPayCount;
		return $data;
	}
	protected function _before_insert(&$data , &$option){
		$memberId = session('m_id');
		//未登录
		if(!$memberId){
			$this->error('请登陆!');
			return false;
		}
		$cartModel = D('Home/cart');
		//$option会被传到其他方法中
		$option['goods'] = $goods = $cartModel->cartlist();
		//购物车没有商品
		if(!$goods){
			$this->error = '购物车空空的，赶快去挑选心仪的商品吧~';
			return false;
		}
		//读库存之前加锁,并赋给这个模型,锁在下单结束时仍然有效
		$this->lock = fopen('./order.lock');//打开锁文件
		flock($this->lock , LOCK_EX);//排他锁
		//检查库存,计算总价
		$gnModel = D('goods_number');
		$total_price = 0;
		foreach ($goods as $k=>$v){
			$gnNumber = $gnModel->field('goods_number')->where(array(
				'goods_id' => $v['goods_id'],
				'goods_attr_id' => $v['goods_attr_id']
			))->find();
			if($gnNumber['goods_number'] < $v['goods_number']){
				$this->error('下单失败,原因,商品:<strong>'.$v['goods_name'].'</strong>库存不足!');
				return false;
			}
			//总价
			$total_price += $v['price']*$v['goods_number'];
		}
		//完善订单信息
		$data['total_price'] = $total_price;
		$data['member_id'] = $memberId;
		$data['addtime'] = time();
		
		//三张表:定单表,定单商品表,商品库存表都能成功,开启事务
		$this->startTrans();
	}
	
	protected function _after_insert($data , $option){
		$ogModel = D('order_goods');
		$gnModel = D('goods_number');
		foreach ($option['goods'] as $k => $v){
			//添加
			$ret = $ogModel -> add(array(
				'order_id' => $data['id'],
				'goods_id' => $v['goods_id'],
				'goods_attr_id' => $v['goods_attr_id'],
				'goods_number' => $v['goods_number'],
				'price' => $v['price'],
			));
			if(!$ret){
				$this->rollback();
				return false;
			}
			//减库存
			$ret = $gnModel -> where(array(
				'goods_id' => $v['goods_id'],
				'goods_attr_id' => $v['goods_attr_id'],
			))->setDec('goods_number' , $v['goods_number']);
			if(false === $ret){
				$this->rollback();
				return false;
			}
		}
		//提交事务
		$this->commit();
		
		//释放锁
		flock($this->lock , LOCK_UN);
		fclose($this->lock);
		
		//清空购物车
		$cartModel = D('Home/cart');
		$cartModel->clear();
	}
	/**
	 * 设置订单已支付后的状态
	 *
	 * @param unknown_type $ordeId
	 */
	public function setPaid($ordeId){
		//更新支付状态
		$this->where(array(
			'id' => array('eq' , $ordeId)
		))->save(array(
			'pay_status' => '是',
			'pay_time' => time()
		));
		//更新会员积分
		$tp = $this->field('total_price,member_id')->find($ordeId);
		$memberModel = M('member');
		$memberModel->where(array(
			'id' => array('eq' , $tp['member_id'])
		))->setInc('jifen' , $tp['total_price']);
	}
}