<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Conversation as ConversationResource;
use App\Http\Resources\Message as MessageResource;
use App\Models\Conversation as Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Create a new ConversationController instance.
     */
    public function __construct()
    {
        $this->middleware('jwt.verify');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $conversations = $user
                            ->conversations()
                            ->orderByLatestMessage()
                            ->paginate();

        return ConversationResource::collection($conversations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Conversation $conversation)
    {
        $data = $request->only('content');
        $sender = auth()->user();
        $receiver = $conversation->users()->where('user_id', '!=', $sender->id)->first();
        $message = $conversation->messages()->create($data);
        $message->author()->associate($sender)->save();

        return new MessageResource($message);
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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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
