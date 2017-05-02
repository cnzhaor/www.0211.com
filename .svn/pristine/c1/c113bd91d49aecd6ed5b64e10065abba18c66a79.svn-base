<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller 
{
    public function login()
    {
		if(IS_POST)
		{
			$model = D('admin');
	    	//接收表单并验证
	    	if($model->validate($model->_login_validate)->create())
	    	{
	    		if($model->login())
	    		{
	    			$this->success('登陆成功!' , U('Index/index'));
	    			exit;
	    		}
	    	}
	    	$this->error($model->getError());
		}
    	$this->display();
    }
    public function chkcode()
    {
    	$Verify = new \Think\Verify(array(
    		'fontsize' => 30,
    		'length'   => 2,
    		'usenoise' => true,
    		'imageW'   => 145
    	));
    	$Verify->entry();
    }
    public function logout()
    {
    	$model = D('admin');
    	$model->logout();
    	redirect('login');
    }
}