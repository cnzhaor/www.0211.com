<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController 
{
    public function index()
    {
    	$this->display();
    }
    public function top()
    {
    	$this->display();
    }
    public function menu()
    {
    	$priModel = D('privilege');
    	$btns = $priModel->getBtns();
//    	dump($btns);die;
    	$this->assign(array(
			'btns' => $btns,
		));
    	$this->display();
    }
    public function main()
    {
    	$this->display();
    }
}