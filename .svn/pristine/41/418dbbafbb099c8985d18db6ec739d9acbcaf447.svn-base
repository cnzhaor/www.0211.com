<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model 
{
	protected $insertFields = array('username','password','cpassword','chkcode','must_click');
	protected $updateFields = array('id','username','password','cpassword');
	//添加和修改会员时使用的验证规则
	protected $_validate = array(
		array('must_click', 'require', '未同意注册协议！', 1, 'regex', 3),
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('password', '6,20', '密码长度必须在6-20个字符之间！', 1, 'length', 3),
		array('username', '', '用户名已经存在！', 1, 'unique', 3),
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		array('cpassword', 'password', '两次密码输入不一致！', 1, 'confirm', 3),
		array('chkcode' , 'require' , '验证码不能为空!' , 1),
		array('chkcode' , 'check_verify' , '验证码输入有误!' , 1 ,'callback'),
		);
	//验证登陆表单
	public $_login_validate = array(
		array('username' , 'require' , '用户名不能为空!' , 1),
		array('password' , 'require' , '密码不能为空!' , 1),
		array('chkcode' , 'require' , '验证码不能为空!' , 1),
		array('chkcode' , 'check_verify' , '验证码输入有误!' , 1 ,'callback'),
	);
	
	//验证验证码
	function check_verify($code , $id = '')
	{
		$verify = new \Think\Verify();
		return $verify->check($code , $id);
	}
	public function login()
	{
		//从模型中获取表单提交的用户名和密码
		$username = $this->username;
		$password = $this->password;
		//查询用户是否存在
		$user = $this->where(array(
			'username' => array('eq' , $username)
		))->find();
		if($user)
		{
			if($user['password'] == md5($password))
			{
				//登陆成功
				session('m_id' , $user['id']);
				session('m_username' , $user['username']);
				//会员级别
				$mlModel = D('member_level');
				$levelId = $mlModel->field('id')->where(array(
					'jifen_bottom' => array('elt' , $user['jifen']),
					'jifen_top' => array('egt' , $user['jifen']),
				))->find();
				session('level_id' , $levelId['id']);
				//将cookie中的购物车数据移到数据库中
				$cartModel = D('Home/cart');
				$cartModel->moveDataToDb();
				return true;
			}
			else
			{
				$this->error ='密码不正确!';
				return false;
			}
		}
		else
		{
			$this->error = '用户名不存在!';
			return false;
		}
	}
	public function logout()
	{
		session(null);
	}
	
	// 添加前
	protected function _before_insert(&$data, $option)
	{
		$data['password'] = md5($data['password']);
	}
	
}