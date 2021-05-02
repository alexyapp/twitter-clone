<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comment as CommentResource;
use App\Models\Comment;
use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tweet $tweet)
    {
        $data = $request->only(['content']);
        $comment = Comment::create($data);
        $comment->author()->associate(auth()->user())->save();
        $tweet->comments()->save($comment);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tweet $tweet, Comment $comment)
    {
        $data = $request->only(['content', 'comment']);
        $reply = Comment::create($data);
        $reply->author()->associate(auth()->user())->save();
        $reply->parentComment()->associate($comment);
        $tweet->comments()->save($reply);

        return new CommentResource($reply);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
