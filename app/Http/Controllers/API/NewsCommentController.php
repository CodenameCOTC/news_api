<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\NewsComment;
use App\News;
use Validator;

class NewsCommentController extends BaseController
{
    public function index(News $news) {
        $comments = NewsComment::with('user')->where('news_id', $news->id)->paginate(10);

        return $this->sendResponse($comments->toArray(), 'Comments retrieved successfully');
    }

   public function store(Request $request, News $news) {
        $userId = auth()->user()->id;
        $newsId = $news->id;
        $input = $request->all();

        $validator = Validator::make($input, [
            'text' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendBadRequest('Validation error', $validator->errors());
        }

        $comment = NewsComment::create([
            'text'=> $input['text'],
            'user_id' => $userId,
            'news_id' => $newsId
        ]);

        $createdComment = NewsComment::with('user')->find($comment['id']);

        return $this->sendResponse($createdComment, 'Comment created successfully');

    }

    public function update(Request $request, NewsComment $newsComment) {
        $userId = auth()->user()->sub;

        if ($userId != $newsComment->user_id) {
            return $this->sendBadRequest('Permission not allowed');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'text' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendBadRequest('Validation error', $validator->errors());
        }

        $newsComment->text = $input['text'];
        $newsComment->save();

        return $this->sendResponse($newsComment->toArray(), 'News updated sucessfully');
    }

    public function destroy(News $news, $id) {
        $userId = auth()->user()->id;
        $newsComment = NewsComment::with('user')->where('id', $id)->first();

        if ($userId != $newsComment->user_id) {
            return $this->sendBadRequest('Permission not allowed');
        }

        $newsComment->delete();

        return $this->sendResponse($newsComment, 'Comment deleted successfully');
    }
}
