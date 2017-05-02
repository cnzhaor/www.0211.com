<?php
namespace Admin\Model;
use Think\Model;
class CommentReplyModel extends Model 
{
	//回复时允许提交的字段
	protected $insertFields = array('content','comment_id');
	//表单验证规则
	protected $_validate = array(
		array('content', '1,200', '内容必须是1-200个字符！', 1 , 'length'),
		array('comment_id', 'require', '参数错误！', 1),
		);
	protected function _before_insert(&$data , $option){
		$memberId = session('m_id');
		if(!$memberId){
			$this -> error = '请登录!';
			return false;
		}
		$data['member_id'] = $memberId;
		$data['addtime'] = date('Y-m-d H:i:s');
	}
}