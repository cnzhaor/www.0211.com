<?php
namespace Home\Controller;
use Think\Controller;
class MyController extends NavController{
	public function __construct(){
		parent::__construct();
		$memberId = session('m_id');
		if(!$memberId){
			session('returnUrl' , U('My/'.ACTION_NAME));
			redirect(U('Member/login'));
		}
	}
	/**
	 * 订单
	 *
	 */
	public function order(){
		$orderModel = D('Admin/order');
		$data = $orderModel->search();
		// 设置页面中的信息
		$this->assign(array(
			'data' =>$data,
			'_page_title' => '个人中心-我的订单',
		));
        $this->display();
	}
	
   
}