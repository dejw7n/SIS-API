<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\DeferredFile;
use App\Models\PostFile;
use App\Models\File;
use App\Models\User;
use App\Models\PostChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class PostController extends Controller
{
    public function showAllPosts()
    {
        //sort by priority
        $posts = DB::table('posts')
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($posts as $post) {
            $post_files = PostFile::where('post_id', $post->id)->get();
            $files = [];
            foreach ($post_files as $post_file) {
                $files[] = File::find($post_file->file_id);
            }
            $post->files = $files;
            $post->user = User::find($post->author_id);
            $post->changes = self::showPostChanges($post->id)->original;
        }
        return response()->json($posts);
    }

    public function showAllFilesOfPost($id)
    {
        $post_files = PostFile::where('post_id', $id)->get();
        $files = [];
        foreach ($post_files as $post_file) {
            $files[] = File::find($post_file->file_id);
        }
        return response()->json($files);
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
            'monitors' => 'required|boolean',
            'session_files' => 'string|nullable',
        ]);
        $user = Auth::user();
        $request->merge(['author_id' => $user->id]);
        $post = Post::create($request->all());
        if ($request->session_files) {
            $deferredFiles = DeferredFile::where('session', $request->session_files)->get();
            foreach ($deferredFiles as $deferredFile) {
                $postFile = new PostFile();
                $postFile->post_id = $post->id;
                $postFile->file_id = $deferredFile->file_id;
                $postFile->save();
                $deferredFile->delete();
            }
        }

        return response()->json($post, 201);
    }

    public function update($id, Request $request)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        //record post change
        $post_change = new PostChange();
        $post_change->post_id = $post->id;
        $post_change->user_id = Auth::user()->id;
        $post_change->save();

        return response()->json($post, 200);
    }

    public function delete($id)
    {
        Post::findOrFail($id)->delete();
        return response(200);
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
