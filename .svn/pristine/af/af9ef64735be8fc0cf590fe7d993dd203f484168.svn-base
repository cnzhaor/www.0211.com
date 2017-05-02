<?php
namespace Home\Controller;
use Think\Controller;
class CommentController extends Controller{
    /**
     * 获取评论
     *
     */
	public function ajaxGetComment(){
    	$goodsId = I('get.goods_id');
    	$model = D('Admin/comment');
    	$data = $model->search($goodsId , 5);
    	$this->ajaxReturn($data);
    }
	//AJAX发表评论
    public function add()
    {
		if(IS_POST)
		{
			$model = D('Admin/comment');
	    	//接收表单并验证,I函数过滤表单
	    	if($model->create(I('post.') , 1))
	    	{
	    		if($model->add())
	    		{
	    			$this->success(array(
	    				'face' => session('face'),
	    				'username' => session('m_username'),
	    				'addtime' => date('Y-m-d H:i:s'),
	    				'content' => I('post.content'),
	    				'star' => I('post.star')
	    			) , '' , TRUE);
	    		}
	    	}
	    	$this->error($model->getError() , '' , TRUE);
		}
    }
    /**
     * ajax回复
     *
     */public function reply(){
    	if(IS_POST){
    		$model = D('Admin/CommentReply');
    		if($model->create(I('post.') , 1)){
    			if($model->add())
	    			$this->success(array(
	    				'face' => session('face'),
	    				'username' => session('m_username'),
	    				'addtime' => date('Y-m-d H:i:s'),
	    				'content' => I('post.content')
	    			) , '' , TRUE);
    		}
    		$this->error($model->getError());
    	}
    }
}