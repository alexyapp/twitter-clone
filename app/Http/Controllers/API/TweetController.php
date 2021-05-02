<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tweet as TweetResource;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Searchable\Search;

class TweetController extends Controller
{
    /**
     * Create a new TweetController instance.
     */
    public function __construct()
    {
        $this->middleware('jwt.verify');
        $this->middleware('throttle:5')->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($q = request()->input('q')) {
            $searchResults = (new Search())
                                ->registerModel(Tweet::class, 'content')
                                ->search($q);

            $tweets = new Collection();

            foreach ($searchResults->groupByType() as $type => $modelSearchResults) {
                if ($type == 'tweets') {
                    foreach ($modelSearchResults as $searchResult) {
                        $tweets->push($searchResult->searchable);
                    }
                }
            }
        } else {
            $tweets = Tweet::latest()->paginate()->load(['comments', 'comments.replies']);
        }

        return TweetResource::collection($tweets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('content');
        $tweet = auth()->user()->tweets()->create($data);

        return new TweetResource($tweet);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Tweet $tweet)
    {
        return new TweetResource($tweet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tweet $tweet)
    {
        $this->authorize('can_modify_or_delete', $tweet);
        $data = $request->only('content');
        $tweet->update($data);

        return new TweetResource($tweet);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tweet $tweet)
    {
        $this->authorize('can_modify_or_delete', $tweet);
        $tweet->delete();

        return new TweetResource($tweet);
    }
}
