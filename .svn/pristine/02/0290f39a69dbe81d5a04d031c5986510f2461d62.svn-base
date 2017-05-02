<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller 
{
    public function __construct()
    {
    	//必须先调用父类控制器
    	parent::__construct();
    	//判断是否登陆
    	if(!session('id'))
    		$this->error('必须先登陆!' , U('Login/login'));
    	//后台首页不限制登陆
    	if(CONTROLLER_NAME == 'Index')
    		return true;
    	$priModel = D('privilege');
    	if(!$priModel->chkPri())
    		$this->error('无权访问!');
    }
}