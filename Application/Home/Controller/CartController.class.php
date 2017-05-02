<?php
namespace Home\Controller;
use Think\Controller;
class CartController extends Controller{
	
	/**
	 * 加入购物车数据库
	 *
	 */
	public function add(){
//		dump($_POST);die;
		if(IS_POST){
			$cartModel = D('cart');
			if($cartModel->create(I('post.'),1)){
				if($cartModel->add()){
					$this->success('添加成功!',U('lst'));
					exit;
				}
			}
			$this->error('添加失败!原因:'.$cartModel->getError());
		}
	}
	
	/**
	 * 删除购物车其中一条记录
	 *
	 */
	public function delete(){
		$cartId = I('get.cart_id');
		$cartModel = D('Cart');
		$ret = $cartModel->delete($cartId);
		if($ret){
			$this->success('删除成功!',U('lst'));
			exit;
		}
		$this->error('删除失败!原因:'.$cartModel->getError());
	}
	
	//列表
	public function lst(){
		$cartModel = D('Cart');
		$data = $cartModel->cartList();
//		dump($data);die;
		// 设置页面中的信息
		$this->assign(array(
			'data' => $data,
			'_page_title' => '购物车列表',
			'_page_keywords' => '购物车列表',
			'_page_description' => '购物车列表'
		));
		$this->display();
	}
	public function ajaxGetCartList(){
		$cartModel = D('Cart');
		$data = $cartModel->cartList();
		if(!empty($data))
			$this->ajaxReturn($data);
	}
}