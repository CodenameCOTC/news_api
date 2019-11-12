<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\News;
use Validator;

class NewsController extends BaseController
{
    public function index(Request $request)
    {
        $searchQuery = $request->query('search', '');
        $news = News::with('user')->where('title', 'LIKE', '%'.$searchQuery.'%')->orderBy('created_at', 'desc')->paginate(10);

        return $this->sendResponse($news, 'News retrieved successfully');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $input = $request->all();

        if ($user->isAdmin == 0) {
            return $this->sendBadRequest('You\'re not have permission to do this');
        }

        $validator = Validator::make($input, [
            'title' => 'required',
            'body' => 'required',
            'images' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $news = News::create([
            'title' => $input['title'],
            'body' => $input['body'],
            'created_by' => $user->id,
        ]);

        return $this->sendResponse($news->toArray(), 'News created successfully');
    }

    public function show($id)
    {
        $news = News::with('user')->with('comments')->find($id);


        if (is_null($news)) {
            return $this->sendError('News not found');
        }


        return $this->sendResponse($news, 'News retrieved successfully');
    }

    public function update(Request $request, News $news)
    {

        $user = auth()->user();

        if ($user->id != $news->created_by) {
            return $this->sendBadRequest('Permission not allowed');
        }


        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'body' => 'required',
            'images' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $news->title = $input['title'];
        $news->body = $input['body'];
        $news->images = $input['images'];
        $news->save();

        return $this->sendResponse($news->toArray(), 'News updated successfully');
    }

    public function destroy(News $news)
    {

        $user = auth()->user();

        if ($user->id != $news->created_by) {
            return $this->sendBadRequest('Permission not allowed');
        }
        
        $news->delete();

        return $this->sendResponse([], 'News deleted successfully');
    }
}
