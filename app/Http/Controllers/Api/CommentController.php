<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use GuzzleHttp\Psr7\Response;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request ,$post)
    { 
        $post=Post::find($post);
        if( !$post ){
            return response()->json([
                "status"=> 0,
                "message"=> "post does not exist"
            ]);
        }
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);
    try{
        $comments = Comment::create([
            'title' => $request->title,
            'description' => $request->description,
            'post_id'=> $request->post_id,//$request->post_id,
            'user_id'=> Auth::user()->id

        ]);

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comments
        ]);
    }catch(\Exception $e) {
        Log::error($e->getMessage());
        return response()->json([
            "status"=> "failed",
            "message"=> "failed"
        ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show($postId, $commentId)
{
    $post = Post::find($postId);

    if (!$post) {
        return response()->json(['message' => 'Post not found.']);
    }

    $comment = $post->comments()->where('id', $commentId)->first();

    if (!$comment) {
        return response()->json(['message' => 'Comment not found for this post.']);
    }

    return response()->json($comment, 200);
}

    /**
     * Show the form for editing the specified resource.
     */
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$post, $comment)
    { 
        
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);
    $post = Post::find($post);
    if (!$post) {
        return response()->json([
            'message'=> 'post does not exist'
        ]);
    }
    
    $response=Comment::find($comment);
    try{
        if($response){
        $response->update([
            'title'=>$validated["title"],
            "description"=>$validated["description"],
        ]);

        return response()->json([
            "status" => 1,
            "message" => "Comment updated",
            "comment" => $response
        ]);}else{
            return response()->json([
                "status" => 0,
                "message" => "Comment doesnot exist",
                "comment" => $response]);
        }
    }catch(\Exception $e){
        Log::error($e->getMessage());
        return response()->json([
            "message" => "failed!!"
        ],500);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($postId,$id)
    
    {
         $post= Post::find($postId);
         if (!$post) {
            return response()->json([
                "status"=>0,
                "message"=> "post does not exist"]);
         }
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
