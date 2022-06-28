<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Mail\NewPostCreated;
use App\Mail\PostUpdatedAdminMessage;
use App\Models\Category;
use Illuminate\Validation\Rule;
use App\Models\Tag;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->get();
        //dd($posts);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        //dd($categories);
        $tags = Tag::all();
        //dd($tags);
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //dd($request->all());

        // Validate data
        $val_data = $request->validated();

        // Gererate slugs
        $slug = Post::generateSlug($request->title);
        $val_data['slug'] = $slug;

        //verify if request contains the file
        //dd($request->hasFile('cover_image'));
        if($request->hasFile('cover_image')) {
            //validate file
            $request->validate([
                'cover_image' => 'nullable|image|max:250'
            ]);
            //save in filesystem
            //dd($request->all());
            $path = Storage::put('post_images', $request->cover_image);
            //dd($path);
            //Pass path to validation array
            $val_data['cover_image'] = $path;
        }

        //dd($val_data);

        // create resource
        $new_post = Post::create($val_data);
        $new_post->tags()->attach($request->tags);

        //Mail preview
        //return (new NewPostCreated($new_post))->render();

        //Send mail
        Mail::to($request->user())->send(new NewPostCreated($new_post)); //First option
        //Mail::to('test@example.com')->send(new NewPostcreated($new_post)); //Second option

        // redirect to a get route
        return redirect()->route('admin.posts.index')->with('message', 'Post Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {   
        //dd($post->tags);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        //dd($categories);
        $tags = Tag::all();
        //dd($tags)

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models|Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        //dd($request->all());

        // Validate data
        $val_data = $request->validated([

            'title' => ['required', Rule::unique('posts')->ignore($post)],
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'exists:tags,id',
            'cover_image' => 'nullable',
            'content' => 'nullable'

        ]);

        // Gererate slugs
        $slug = Post::generateSlug($request->title);
        $val_data['slug'] = $slug;

        //verify if request contains the file
        //dd($request->hasFile('cover_image'));
        if($request->hasFile('cover_image')) {
            //validate file
            $request->validate([
                'cover_image' => 'nullable|image|max:250'
            ]);
            //delete file from filesystem
            Storage::delete($post->cover_image);
            //save in filesystem
            //dd($request->all());
            $path = Storage::put('post_images', $request->cover_image);
            //dd($path);
            //Pass path to validation array
            $val_data['cover_image'] = $path;
        }

        //update resource
        $post->update($val_data);

        $post->tags()->sync($request->tags);

        //Mail Preview
        //return (new PostUpdatedAdminMessage($post))->render();

        //Send mail
        Mail::to('admin@boolpress.it')->send(new PostUpdatedAdminMessage($post));
        
        // redirect to a get route
        return redirect()->route('admin.posts.index')->with('message', 'Post Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        Storage::delete($post->cover_image);
        return redirect()->route('admin.posts.index')->with('message', "$post->title deleted successfully");
    }
}
