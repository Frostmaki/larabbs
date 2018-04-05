<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class TopicsController extends Controller
{
    //
    public $topic;
    public function __construct(){

    }

    //帖子列表
    public function index(Request $request,Topic $topic){

        $topic=$topic->WithOrder($request->order)->paginate(20);

        return $this->response->array($topic,new TopicTransformer());
    }

    //帖子详情
    public function show($id){

        $topic=Topic::all()->where('id',$id);
        return $this->response->array($topic);
    }


    //创建帖子
    public function store(TopicRequest $request,Topic $topic){
        $topic->title=$request->title;
        $topic->body=$request->body;
        $topic->category_id=$request->category_id;
        $topic->user_id=$this->user()->id;

        $topic->save();

        return $this->response()->array($topic,new TopicTransformer());
    }

    //修改帖子
    public function update(TopicRequest $request,$id){
        //$data = Input::all();
        //return $this->response->array($data);


        $topic=Topic::all()->where('id',$id);

        //$this->authorize('update', $topic);

        $result=DB::table('topics')->where('id',$id)->update(['title'=>$request->title,'body'=>$request->body,'category_id'=>$request->category_id]);

        $topic=Topic::all()->where('id',$id);

        if($result==1){
            $result=(['1'=>'更新成功']);
        }else{
            $result=(['0'=>'更新失败']);
         }
        return $this->response->item($topic, new TopicTransformer())->addMeta('111','happy');

    }

    public function destroy($id)
    {
        //$this->authorize('update', $topic);

        DB::table('topics')->where('id',$id)->delete();
        return $this->response->noContent();
    }
}
