<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Tables\Posts;
use App\Models\Category;
use ProtoneMedia\Splade\Facades\Toast;
use App\Http\Requests\PostStoreRequest;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index', ['posts' => Posts::class]);
    }

    public function show()
    {
        
    }

    public function create()
    {
        $categories = Category::pluck('name', 'id')->toArray();
        Toast::title('New post created successfully.');

        return view('posts.create', compact('categories'));
    }

    public function store(PostStoreRequest $request)
    {
        Post::create($request->validated());
        
        return to_route('posts.index');
    }

    public function edit(Post $post)
    {
        $categories = Category::pluck('name', 'id')->toArray();
        return view('categories.edit', compact('post'));
    }

    public function update(PostStoreRequest $request, Post $post)
    {
        $post->update($request->validated());
        Toast::title('Post updated successfully.');
        
        return to_route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        Toast::title('Post deleted successfully.');

        return redirect()->back();
    }
}
