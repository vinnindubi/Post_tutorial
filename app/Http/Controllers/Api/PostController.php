<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
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
    public function store(StorePostRequest $request)
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
        $posts=auth()->user()->posts()->create($request->all());

    return response()->json([
        "status"=> "success",
        "message"=> "post created",
        "data"=>$posts
    ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {

      $data=Post::find($post->id);
      return response()->json([
        "status"=> "success",
        "message"=> "post fetched",
        "data"=>$data
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
    
        return response()->json([
            "status" => 1,
            "message" => "Post updated",
            "data" => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json([
            "status"=> 1,
            "message"=> "post deleted successfully"
        ]);
    }
}
