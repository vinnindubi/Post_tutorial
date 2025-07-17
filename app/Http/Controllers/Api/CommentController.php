<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use GuzzleHttp\Psr7\Response;
use App\Models\Post;
use App\Models\User;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Comment $comment)
    {
        $comment=Comment::find($comment->id);
        return response()->json([
            'status'=>1,
            'message'=>"comments loaded successfully",
            "data"=> $comment
        ]);
        if(!$comment){
            return response()->json([
                "status"=> 0,
                "message"=> "failed to load comments",
                "data"=>null,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $comments = auth()->user()->comments()->create([
        'title' => $request->title,
        'description' => $request->description,
        "post_id"=> $request->post,
        'user_id' => auth()->id(),
    ]);

    return response()->json([
        'message' => 'Comment created successfully',
        'comment' => $comments
    ], 201);
}


    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
