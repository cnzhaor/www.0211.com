<?php
namespace Admin\Model;
use Think\Model;
class CommentModel extends Model 
{
	//评论时允许提交的字段
	protected $insertFields = array('star','content','goods_id');
	//表单验证规则
	protected $_validate = array(
		array('star', '1,2,3,4,5', '分值只能是1-5之间的数字！', 1 , 'in'),
		array('content', '1,200', '内容必须是1-200个字符！', 1 , 'length'),
		array('goods_id', 'require', '参数错误！', 1),
		);
	protected function _before_insert(&$data , $option){
		$memberId = session('m_id');
		if(!$memberId){
			$this -> error = '请登录!';
			return false;
		}
		$data['member_id'] = $memberId;
		$data['addtime'] = date('Y-m-d H:i:s');
		
		//买家印象
		$yxId = I('post.yx_id');//现有印象
		$yxName = I('post.yx_name');
		$yxModel = D('Yinxiang');
		if($yxId){
			foreach ($yxId as $k=>$v)
				$yxModel->where(array('id'=>$v))->setInc('yx_count');
		}
		if($yxName){
			//替换中文逗号
			$yxName = str_replace('，',',',$yxName);
			$yxName = explode(',',$yxName);
			foreach ($yxName as $k => $v){
				//去掉空格
				$v = trim($v);
				if(empty($v))
					continue;
				//印象是否已经存在
				$has = $yxModel->where(array(
					'goods_id' => $data['goods_id'],
					'yx_name' => $v
				))->find();
				if($has)
					$yxModel->where(array(
						'goods_id' => $data['goods_id'],
						'yx_name' => $v
					))->setInc('yx_count');
				else
					$yxModel->add(array(
						'goods_id' => $data['goods_id'],
						'yx_name' => $v,
						'yx_count' => 1
					));
			}
		}
	}
	
	/**
	 * 显示评价和AJAX翻页
	 *
	 * @param unknown_type $goodsId
	 * @param unknown_type $pageSize
	 */
	public function search($goodsId , $pageSize = 5){
		$where['a.goods_id'] = array('eq' , $goodsId);
		//记录数
		$count = $this->alias('a')->where($where)->count();
		//计算总的页数
		$pageCount = ceil($count / $pageSize);
		//获取当前页
		$currentPage = max(1 , (int)I('get.p' , 1));//屏蔽负数
		//偏移量
		$offset = ($currentPage -1) * $pageSize;
		
		//当获取第一页的评论数据时，计算一此好评率和买家印象
		if($currentPage == 1){
			//所有分值
			$stars = $this->field('star')->where(array(
				'goods_id' => $goodsId
			))->select();
			//分别统计好中差
			$hao = $zhong = $cha = 0;
			foreach ($stars as $k=>$v){
				if($v['star'] == 3)
					$zhong++;
				elseif ($v['star'] > 3)
					$hao++;
				else 
					$cha++;
			}
			$total = $hao + $zhong + $cha;
			//比率四舍五入
			$hao = round($hao/$total*100 , 2);
			$zhong = round($zhong/$total*100 , 2);
			$cha = round($cha/$total*100 , 2);
			//印象
			$yxModel = D('Yinxiang');
			$yxData = $yxModel->where(array(
				'goods_id' => $goodsId
			))->select();
		}
		
		//取评论
		$data = $this->alias('a')
		->field('a.id,a.content,a.addtime,a.star,a.click_count,b.face,b.username,COUNT(c.id) reply_count')
		->join('LEFT JOIN __MEMBER__ b ON a.member_id=b.id
				LEFT JOIN __COMMENT_REPLY__ c ON a.id=c.comment_id')
		->where($where)
		->order('a.id DESC')
		->limit("$offset,$pageSize")
		->group('a.id')
		->select();
		
		//循环每个评论并取回复
		$crModel = D('Comment_reply');
		foreach ($data as $k => &$v){
			$v['reply'] = $crModel->alias('a')
							->field('a.content,a.addtime,b.username,b.face')
							->join('LEFT JOIN __MEMBER__ b ON a.member_id=b.id')
							->where(array(
								'a.comment_id' => $v['id']
							))
							->order('a.id ASC')
							->select();
		}
		return array(
			'data' => $data,
			'pageCount' => $pageCount,
			'hao' => $hao,
			'zhong' => $zhong,
			'cha' => $cha,
			'yxData' => $yxData,
			'memberId' => (int)session('m_id')
		);
	}
}