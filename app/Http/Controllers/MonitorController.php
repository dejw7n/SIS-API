<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitorController extends Controller
{
    public function showPostsByCenter($center)
    {
        $posts = Post::all()
            ->where('center_id', $center)
            ->where('monitors', true);
        foreach ($posts as $post) {
            $post->user = User::find($post->author_id);
        }
        return response()->json($posts);
    }

    public function showPostChanges($id)
    {
        $post_changes = PostChange::where('post_id', $id)->get();
        foreach ($post_changes as $post_change) {
            $post_change->user = User::find($post_change->user_id);
        }
        return response()->json($post_changes);
    }
}
