<?php
namespace Home\Controller;
use Think\Controller;
class NavController extends Controller 
{
    public function __construct()
    {
		//必须先调用父类的构造函数
		parent::__construct();
		$catModel = D('Admin/category');
		$catData = $catModel -> getNavData();
//		dump($catData);die;
		$this->assign(array(
			'catData' => $catData
		));
    }
}