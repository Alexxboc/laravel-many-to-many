@extends('layouts.admin')


@section('content')

<div class="posts d-flex py-4">
    <img width="450" class="img-fluid" src="{{asset('storage/' . $post->cover_image)}}" alt="{{$post->title}}">

    <div class="post-data px-4">
        <div class="meta">
            <div class="category">
                <strong>Category:</strong>
                <span>{{$post->category ? $post->category->name : 'N/A'}}</span>
            </div>
            <div class="tags">
                @if(count($post->tags) > 0)
                <strong>Tags:</strong>
                @foreach($post->tags as $tag)
                <span>#{{$tag->name}}</span>
                @endforeach
                @else
                <span>N/A</span>
                @endif
            </div>
        </div>
        <h1>{{$post->title}}</h1>
        <div class="content">
            {{$post->content}}
        </div>
    </div>
</div>


@endsection