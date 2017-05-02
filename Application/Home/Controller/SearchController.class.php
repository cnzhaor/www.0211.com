<?php
namespace Home\Controller;
use Think\Controller;
class SearchController extends NavController{
	//分类搜索
	public function cat_search(){
		$catId = I('get.cat_id');
		
		//取出商品和翻页
		$goodsModel = D('Admin/goods');
		$data = $goodsModel->cat_search($catId);
		//根据上面搜索出来的商品计算筛选条件
		$catModel = D('Admin/category');
		$searchFilter = $catModel->getSearchConditionByGoodsId($data['goods_id']);
//		dump($data);die;
		// 设置页面中的信息
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page'],
			'searchFilter' => $searchFilter,
			'_page_title' => '分类搜索页',
			'_page_keywords' => '分类搜索页',
			'_page_description' => '分类搜索页'
		));
        $this->display();
	}
	
	/**
	 * 关键字搜索
	 *
	 */
	public function key_search(){
		//搜索关键字
		$key = I('get.key');
		
		//sphinx
		require('./sphinxapi.php');
		$sph = new \SphinxClient();
		$sph->SetServer('localhost' , '9312');
		//过滤出属性is_updated=0的
		$sph->SetFilter('is_updated' , array(0));
		
		//搜索参数:关键字,索引名字默认是goods
		$ret = $sph->Query($key , 'goods');
		$ids = array_keys($ret['matches']);
		if($ids){
			$gModel = D('Admin/goods');
			$ret = $gModel->field('id,goods_name')->where(array(
				'id' => array('in' , $ids)
			))->select();
		}
		else 
			$ret = false;
		dump($ret);
	}
}