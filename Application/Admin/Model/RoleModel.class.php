<?php
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model 
{
	protected $insertFields = array('role_name');
	protected $updateFields = array('id','role_name');
	protected $_validate = array(
		array('role_name', 'require', '角色名称不能为空！', 1, 'regex', 3),
		array('role_name', '1,30', '角色名称的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('role_name', '', '角色名称已经存在！', 1, 'unique', 3),
	);
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')
		->field('a.*,GROUP_CONCAT(c.pri_name) pri_name')
		->join('LEFT JOIN __ROLE_PRI__ b ON a.id=b.role_id
				LEFT JOIN __PRIVILEGE__ c ON b.pri_id=c.id')
		->where($where)
		->limit($page->firstRow.','.$page->listRows)
		->group('a.id')
		->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
		//先删除修改之前的权限
		$priId = I('post.pri_id');
		$rpModel = D('role_pri');
		$rpModel->where(array(
			'role_id' => array('eq' , $option['where']['id'])
		))->delete();
		foreach ($priId as $k=>$v)
		{
			$rpModel->add(array(
				'pri_id'=>$v,
				'role_id'=>$option['where']['id']
			));
		}
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
		//删除相关中间表的数据
		$rpModel = D('role_pri');
		$rpModel->where(array(
		 'role_id'=>array('eq' , $option['where']['id'])
		))->delete();
		
		$arModel = D('admin_role');
		$arModel->where(array(
		 'role_id'=>array('eq' , $option['where']['id'])
		))->delete();
	}
	/************************************ 其他方法 ********************************************/
	protected function _after_insert($data,$option)
	{
		$priId = I('post.pri_id');
		$rpModel = D('role_pri');
		foreach ($priId as $k=>$v)
		{
			$rpModel->add(array(
				'pri_id' => $v,
				'role_id' => $data['id']
			));
		}
	}
}