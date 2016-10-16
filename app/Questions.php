<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Request;

class Questions extends Model
{
    public function add_question(){
        //路由在任何地方都能访问到
        if(!user_init()->is_login()) return ['status'=>0,'info'=>'请先登录'];
        if(!Request::get('title')) return ['status'=>0,'info'=>'title不能为空'];
        $this->title=Request::get('title');
        $this->user_id=Session::get('user_id');
        //如果有content就保存
        if(Request::get('content'))
            $this->content=Request::get('content');
        return $this->save()?['status'=>1,'info'=>'保存成功']:['status'=>0,'info'=>'保存失败'];

    }
    //更新
    public function updata_question(){
        if(!user_init()->is_login()) return ['status'=>0,'info'=>'请先登录'];
        //必须传递修改的id
        if(!Request::get('id')) return ['status'=>0,'info'=>'无id'];
        //必须是发布者才能修改
        $ret=$this->find(Request::get('id'));
        if(!$ret) return ['status'=>0,'info'=>'查询不到相关数据'];;
        //把具体的数据找出来让后保存
        if($ret->user_id!=Session::get('user_id')) return ['status'=>0,'info'=>'无权修改'];
        if(Request::get('title')) $ret->title=Request::get('title');
        if(Request::get('content')) $ret->content=Request::get('content');
        //保存更新
        return $ret->save()?['status'=>0,'info'=>'修改成功']:['status'=>1,'info'=>'修改失败'];
    }
    //查询
    public function red_question(){
        if(Request::get('id')) return ['status'=>1,$this->find(Request::get('id'))];
        $limit=5;
        //查看第几页的逻辑处理
        $skip=((Request::get('page')?:1)-1)*5;
        $data=$this->orderBy('created_at')->limit($limit)->skip($skip)->get(['id','title','content','created_at']);
        return ['status'=>1,'info'=>$data];
    }
    //删除
    public function del_question(){
        if(!Request::get('id')) return ['status'=>0,'info'=>'无id'];
        $data=$this->find(Request::get('id'));
        if($data->user_id!=Session::get('user_id')) return ['status'=>0,'info'=>'无权限'];
        return $data->delete() ? ['status'=>0,'info'=>'删除成功']:['status'=>1,'info'=>'删除失败'];
    }
}
