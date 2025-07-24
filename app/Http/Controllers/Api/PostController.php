<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { $user=Auth::user();
        // $this->authorize('viewAny',Post::class);
        
        $posts=Post::paginate();
        return response()->json([
            "status"=> "success",
            "message"=>"posts fetched",
            "data"=>$posts
        ]);
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
    public function store(Request $request)
    {
        $validated= $request->validate([
            "title"=> "required",
            "description"=>["required","string"],
        ]);
        
        if(!$validated){
            return response()->json([
                "status"=>0,
                "message"=> "validation failed"
            ]);
        }
        try {
          
            $posts=Post::create([
                "title"=> $validated["title"],
                "description"=> $validated["description"],
                "user_id"=>Auth::user()->id,
            ]);
    
        return response()->json([
            "status"=> "success",
            "message"=> "post created",
            "data"=>$posts
        ]);
        }
        catch(\Exception $e) {
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
    public function show($id)
    {
        $post = Post::find($id);
        if($post){
            
            return response()->json([
                "status"=> "success",
                "message"=> "post fetched",
                "data"=>$post
              ]);
        }else{
            
            return response()->json([
                "status"=> 0,
                "message"=> "post not found"
            ]);
        }
      
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        try{
            if($post){
            $post->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
            ]);
        
            return response()->json([
                "status" => 1,
                "message" => "Post updated",
                "data" => $post
            ]);}else{
                return response()->json([
                    "status"=> 0,
                    "message"=> "post not found"
                ]);
            }
        }
        catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "status"=> "failed",
                "message"=> "failed"
            ]);
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if($post){
            $post->delete();
            return response()->json([
                "status"=> 1,
                "message"=> "post deleted successfully"
            ]);
        }else{
            
            return response()->json([
                "status"=> 0,
                "message"=> "post not found"
            ]);
        }
       
    }
}
