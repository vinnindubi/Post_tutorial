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
    public function index(Post $post )
    {
        $response=Comment::find($post->comments);
        if(!$response){
            return response()->json([
                "status"=> 0,
                "message"=> "failed to load comments",
                "data"=>null,
            ]);
        }
                return response()->json([
            'status'=>1,
            'message'=>"comments loaded successfully",
            "data"=> $response
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $comments = Comment::create([
        'title' => $request->title,
        'description' => $request->description,
        'post_id'=> $request->post_id,
        'user_id'=> $request->user_id

    ]);

    return response()->json([
        'message' => 'Comment created successfully',
        'comment' => $comments
    ], 201);
}


    /**
     * Display the specified resource.
     */
    public function show($postId, $commentId)
{
    $post = Post::find($postId);

    if (!$post) {
        return response()->json(['message' => 'Post not found.'], 404);
    }

    $comment = $post->comments()->where('id', $commentId)->first();

    if (!$comment) {
        return response()->json(['message' => 'Comment not found for this post.'], 404);
    }

    return response()->json($comment, 200);
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
    public function destroy($postId,$id)
    
    {
         $post= Post::find($postId);
        $response=$post->comments()->where('id', $id)->first();
        
        if(!$response){
            return response()->json([
                "status"=> 0,
                "message"=> "comment does not exist"
            ]);
        }
        
        $response->delete();

        return response()->json([
            "status"=> 1,
            "message"=> "user deleted successfully",
            "data"=>$response

        ]);
    }
}
