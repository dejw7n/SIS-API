<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\PostChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitorController extends Controller
{
    public function showPostsByCenter($center)
    {
        $posts = Post::all()
            ->where('center_id', [$center, 3])
            ->where('monitors', true);
        foreach ($posts as $post) {
            $post->author = User::find($post->author_id);
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
