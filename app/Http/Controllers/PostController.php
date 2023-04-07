<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function showAllPosts()
    {
        return response()->json(Post::all());
    }

    public function showOnePost($id)
    {
        return response()->json(Post::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'content' => 'required|string',
            'priority_id' => 'required|exists:priorities,id',
            'center_id' => 'required|exists:centers,id',
        ]);
        $user = Auth::user();
        $request->merge(['author_id' => $user->id]);
        $post = Post::create($request->all());

        return response()->json($post, 201);
    }

    public function update($id, Request $request)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());

        return response()->json($post, 200);
    }

    public function delete($id)
    {
        Post::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
