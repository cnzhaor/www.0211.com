<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends Controller{
	public function ajaxChkLogin(){
		if(session('m_id'))
			$data = array(
				'login' => 1,
				'username' => session('m_username')
			);
		else
			$data = array(
				'login' => 0
			);
			$this->ajaxReturn($data);
	}
	//验证码
	public function chkcode(){
    	$Verify = new \Think\Verify(array(
    		'fontsize' => 30,
    		'length'   => 2,
    		'usenoise' => true,
    		'imageW'   => 145
    	));
    	$Verify->entry();
    }
    //登陆
    public function login()
    {
		if(IS_POST)
		{
			$model = D('Admin/member');
	    	//接收表单并验证
	    	if($model->validate($model->_login_validate)->create())
	    	{
	    		if($model->login())
	    		{
	    			//默认跳转地址
	    			$returnUrl = U('/');
	    			//如果session中有一个跳转地址就跳过去
	    			if(session('returnUrl')){
	    				$returnUrl = session('returnUrl');
	    				session('returnUrl' , null);
	    			}
	    			$this->success('登陆成功!' , $returnUrl);
	    			exit;
	    		}
	    	}
	    	$this->error($model->getError());
		}
		// 设置页面中的信息
		$this->assign(array(
			'_page_title' => '登陆',
			'_page_keywords' => '登陆',
			'_page_description' => '登陆'
		));
    	$this->display();
    }
	public function regist(){
		if($_POST){
			$model = D('Admin/member');
			if($model->create(I('post.'),1)){
				if($model->add()){
					$this->success('注册成功!请登陆:' , U('login'));
					exit;
				}
			}
			$this->error($model->getError());
		}
		// 设置页面中的信息
		$this->assign(array(
			'_page_title' => '注册',
			'_page_keywords' => '注册',
			'_page_description' => '注册'
		));
        $this->display();
    }
    //退出
    public function logout()
    {
    	$model = D('Admin/member');
    	$model->logout();
    	redirect('/');
    }
}