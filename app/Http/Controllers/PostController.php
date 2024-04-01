<?php

namespace App\Http\Controllers;

use App\Http\Requests\storePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'status' => 'Success',
            'posts' => $posts
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storePostRequest $request)
    {
        try {
            DB::beginTransaction();
            $posts = Post::create([
                'title'=> $request->title,
                'category'=> $request->category,
                'content'=> $request->content,
                'author'=> $request->author,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => $th
            ],500);
        }
        return response()->json([
            'message' => 'Post Created Successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            'status' => 'Success',
            'post' => $post
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'=> 'nullable|string',
            'category'=> 'nullable|string|max:30',
            'content'=> 'nullable|string|min:30',
            'author'=> 'nullable|string|max:30',
        ]);
        try {
            $post->update([
                'title'=> $request->title,
                'category'=> $request->category,
                'content'=> $request->content,
                'author'=> $request->author,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th
            ],500);
        }
        return response()->json([
            'message' => 'Post Updated Successfully',
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json([
            'message' => 'Post Deleted Successfully',
        ]);
    }
}
