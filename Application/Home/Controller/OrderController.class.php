<?php
namespace Home\Controller;
use Think\Controller;
class OrderController extends Controller{
	//下单
	public function add(){
		$memberId = session('m_id');
		if(!$memberId){
			//登陆后跳回的地址
			session('returnUrl' , U('Order/add'));
			redirect(U('Member/login'));
			//$this->error('请登录!' , U('Member/login'));
		}
		if(IS_POST){
//			die;// 抓取提交定单时提交的HTML协议
			$orderModel = D('Admin/order');
			if($orderModel->create(I('post') , 1)){
				if($orderId = $orderModel->add()){
					$this->success('下单成功!' , U('order_success?order_id=' . $orderId));
					exit;
				}
			}
			$this->error($orderModel->getError());
		}
		//取得购物车商品信息
		$cartModel = D('cart');
		$data = $cartModel->cartList();
		// 设置页面信息
		$this->assign(array(
		'data' => $data,
		'_page_title' => '定单确认页',
		));
		$this->display();
	}
	/**
	 * 下单成功
	 *
	 */
	public function order_success(){
		$btn = makeAlipayBtn(I('get.order_id'));
		// 设置页面信息
			$this->assign(array(
			'btn' => $btn,
			'_page_title' => '下单成功',
		));
		$this->display();
	}
	/**
	 * 接收支付宝发来的支付成功信息
	 *
	 */
	public function receive(){
			require('./alipay/notify_url.php');
	}
}