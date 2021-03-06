<?php

namespace App\Http\Controllers\Api;


use App\Models\Topic;
use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ReplyRequest;
use App\Transformers\ReplyTransformer;



class RepliesController extends Controller
{
    //
    public function store(ReplyRequest $request,$topic, Reply $reply)
    {
        $reply->content = $request->content;
        $reply->topic_id = $topic;
        $reply->user_id = $this->user()->id;
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }

    public function destroy(Topic $topic, Reply $reply)
    {
        if ($reply->topic_id != $topic->id) {
            return $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }
}
