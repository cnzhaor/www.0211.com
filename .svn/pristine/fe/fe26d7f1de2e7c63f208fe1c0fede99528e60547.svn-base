<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends NavController{
	//ajax获取会员价格
	public function ajaxGetMemberPrice(){
		$goodsId = I('get.goods_id');
		$goodsModel =D('Admin/goods');
		echo $goodsModel->getMemberPrice($goodsId);
	}
	
	//浏览历史
	public function displayHistory(){
		$id = I('get.id');
		//取出cookie中的浏览历史id数组
		$data = isset($_COOKIE['display_history']) ? unserialize($_COOKIE['display_history']) : array();
		//最新浏览的放在数组前面
		if($id)
			array_unshift($data,$id);
		//去重
		$data = array_unique($data);
		//取前6个
		if(count($data)>6)
			$data = array_slice($data , 0 , 6);
		//存cookie
		setcookie('display_history',serialize($data),time()+30*86400,'/');
		$goodsModel = D('Admin/goods');
		$data = implode(',',$data);
		$gData = $goodsModel->field('id,goods_name,mid_logo')->where(array(
			'id' => array('in' , $data),
			'is_on_sale' => array('eq' , '是')
		))->order("FIELD(id,$data)")->select();
		$this->ajaxReturn($gData);
//		echo json_encode($gData);
	}
    public function index(){
    	//测试页面静态化'雪崩'的问题,如果静态页失效瞬间,多用户同时成功访问数据库,会生成多个文件
//    	$file = uniqid();
//    	file_put_contents('./piao/'.$file , 'abc');
    	//取出疯狂抢购的商品
    	$goodsModel = D('Admin/goods');
    	$goods1 = $goodsModel-> getPromoteGoods();
    	$goods2 = $goodsModel-> getRecGoods('is_new');
    	$goods3 = $goodsModel-> getRecGoods('is_hot');
    	$goods4 = $goodsModel-> getRecGoods('is_best');
    	$catModel = D('Admin/category');
    	$floorData = $catModel->getFloorData();
//    	dump($goods1);die;
		// 设置页面中的信息
		$this->assign(array(
			'goods1' => $goods1,
			'goods2' => $goods2,
			'goods3' => $goods3,
			'goods4' => $goods4,
			'floorData' => $floorData,
			'_show_nav' => 1,
			'_page_title' => '首页',
			'_page_keywords' => '首页',
			'_page_description' => '首页'
		));
        $this->display();
    }
    public function goods(){
    	//接收商品ID
    	$id = I('get.id');
    	//商品信息
    	$goodsModel = D('Admin/goods');
    	$info = $goodsModel->find($id);
    	//根据主分类id找出所有上级分类,制作面包屑导航
    	$catModel = D('Admin/category');
    	$catPath = $catModel->parentPath($info['cat_id']);
    	//取出商品相册
    	$gpModel = D('Admin/goods_pic');
    	$gpData = $gpModel->where(array(
    		'goods_id' => array('eq' , $id)
    	))->select();
    	//find()返回一维数组,select()返回二维数组
    	
    	//取出商品的所有属性
    	$gaModel = D('Admin/goods_attr');
    	$gaData = $gaModel->alias('a')
    	->field('a.*,b.attr_name,b.attr_type')
    	->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id=b.id')
    	->where(array(
    		'goods_id' => array('eq' , $id)
    	))->select();
	    	//将多选,唯一属性分开
	    	$uniAttr = array();
	    	$mulAttr = array();
	    	foreach ($gaData as $k=>$v){
	    		if($v['attr_type'] == '可选')
	    			//按照属性分组显示
	    			$mulAttr[$v[attr_name]][] = $v;
	    		else
	    			$uniAttr[] = $v;
	    	}
//    	dump($mulAttr);die;

		//会员价格
		$mpModel = D('member_price');
		$mpData = $mpModel->alias('a')
		->field('a.*,b.level_name')
		->join('LEFT JOIN __MEMBER_LEVEL__ b ON a.level_id=b.id')
		->where(array(
    		'goods_id' => array('eq' , $id)
    	))->select();
    	
    	//配置文件中取得图片显示路径
    	$viewPath = C('IMAGE_CONFIG');
		// 设置页面中的信息
		$this->assign(array(
			'info' => $info,
			'catPath' => $catPath,
			'gpData' => $gpData,
			'uniAttr' => $uniAttr,
			'mulAttr' => $mulAttr,
			'mpData' => $mpData,
			'viewPath' => $viewPath['viewPath'],
			'_show_nav' => 0,
			'_page_title' => '商品详情页',
			'_page_keywords' => '商品详情页',
			'_page_description' => '商品详情页'
		));
        $this->display();
    }
}