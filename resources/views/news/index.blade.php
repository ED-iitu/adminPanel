@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-5">
            <h3>Список новостей</h3>
        </div>
        <div>
            <a class="btn btn-success" href="{{route('news.create')}}">Добавить новость</a>
        </div>
        @if($news->isEmpty())
            <div class="row justify-content-center">
                <h1>Список новостей пуст</h1>
            </div>
        @else
        <ul class="list-group mt-3">
            @foreach ($news as $new)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{route('news.show', $new)}}">{{$new->title}}</a>
                <div>

                    <form action="{{ route('news.destroy',$new->id) }}" method="POST">
                        <a class="btn btn-primary edit" href="{{ route('news.edit', $new->id) }}">Изменить</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger edit" onclick="return confirm('Вы действительно хотите удалить новость?')">Удалить</button>
                    </form>
                </div>

            </li>
            @endforeach
        </ul>
        @endif
    </div>
@endsection