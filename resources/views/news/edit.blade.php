@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Новость: {{$news->title}}</h2>
                </div>
                <div>
                    <a class="btn btn-primary" href="{{ route('news.index') }}"> Вернуться назад</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <h1></h1>
        <hr>
        <form action="{{route('news.update', $news)}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <input type="text" class="form-control" id="uname" placeholder="Заголовок" name="title" required value="{{$news->title}}">
            </div>
            <textarea class="form-control" id="shortText" rows="3" name="short_text" placeholder="Краткое описание">{{$news->short_text}}</textarea>
            <textarea class="form-control mt-3" id="longText" rows="4" name="full_text" placeholder="Полное описание">{{$news->full_text}}</textarea>

            <div style="width: 300px; height: 300px" class="mt-5">
                <img src="{{$news->image}}" alt="" style="max-height: 100%">
            </div>

            <div class="form-group mt-3">
                <label for="image">Выберите картинку</label>
                <input id="image" type="file" name="image">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>

@endsection