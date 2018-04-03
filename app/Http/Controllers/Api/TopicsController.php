<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;


class TopicsController extends Controller
{
    //
    public function index(Request $request,Topic $topic){

        $topic=$topic->WithOrder($request->order)->paginate(20);

        return $this->response->array($topic,new TopicTransformer());
    }

    public function show(Request $request,Topic $topic){

        //$topic=$topic->query()->where($request->id);
        return $this->response->array($request);
    }


}
