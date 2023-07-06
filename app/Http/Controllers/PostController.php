<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    
    public function index()
    {
        return Inertia::render('Posts/Index', [
            'posts' => Post::with('user:id,name')->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        // Validar los datos
        $validate =  $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:255'
        ]);

        $request->user()->posts()->create($validate);

        return redirect(route('posts.index'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validate =  $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:255'
        ]);

        $post->update($validate);

        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect(route('posts.index'));
    }
}
