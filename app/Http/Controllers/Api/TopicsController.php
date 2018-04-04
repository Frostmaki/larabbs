<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


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
        $data = Input::all();
        return $this->response->array($data);
        //$this->authorize('update', $topic);

        $topic=Topic::all()->where('id',$id);

        $topic->title=$request->title;
        $topic->body=$request->body;
        $topic->category_id=$request->category_id;

        return $this->response->item($topic, new TopicTransformer());
    }
}
